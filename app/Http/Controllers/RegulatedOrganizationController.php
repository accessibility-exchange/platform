<?php

namespace App\Http\Controllers;

use App\Enums\ProvinceOrTerritory;
use App\Enums\RegulatedOrganizationType;
use App\Http\Requests\DestroyRegulatedOrganizationRequest;
use App\Http\Requests\StoreRegulatedOrganizationLanguagesRequest;
use App\Http\Requests\StoreRegulatedOrganizationRequest;
use App\Http\Requests\StoreRegulatedOrganizationTypeRequest;
use App\Http\Requests\UpdateRegulatedOrganizationRequest;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\Sector;
use App\Statuses\RegulatedOrganizationStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelOptions\Options;

class RegulatedOrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('regulated-organizations.index', ['regulatedOrganizations' => RegulatedOrganization::status(new RegulatedOrganizationStatus('published'))->orderBy('name')->get()]);
    }

    /**
     * Show a type selection page for the regulated organization.
     */
    public function showTypeSelection(): View
    {
        return view('regulated-organizations.show-type-selection', [
            'types' => Options::forEnum(RegulatedOrganizationType::class)->toArray(),
        ]);
    }

    /**
     * Store the regulated organization's name in the session.
     */
    public function storeType(StoreRegulatedOrganizationTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('type', $data['type']);

        return redirect(localized_route('regulated-organizations.create'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('regulated-organizations.create-initial', [
            'type' => session()->get('type'),
        ]);
    }

    /**
     * Store the model.
     */
    public function store(StoreRegulatedOrganizationRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $data['contact_person_name'] = $user->name;
        $data['contact_person_email'] = $user->email;
        $data['preferred_contact_method'] = 'email';
        $data['languages'] = get_supported_locales(false);

        $regulatedOrganization = RegulatedOrganization::create($data);

        session()->forget('type');

        $regulatedOrganization->users()->attach(
            $request->user(),
            ['role' => 'admin']
        );

        return redirect(localized_route('dashboard'));
    }

    /**
     * Show a language selection page for the logged-in user.
     */
    public function showLanguageSelection(RegulatedOrganization $regulatedOrganization): View
    {
        return view('regulated-organizations.show-language-selection', [
            'regulatedOrganization' => $regulatedOrganization,
        ]);
    }

    /**
     * Update the languages of a resource.
     */
    public function storeLanguages(StoreRegulatedOrganizationLanguagesRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $regulatedOrganization->fill($request->validated());
        $regulatedOrganization->save();

        return redirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    /**
     * Display the specified resource.
     */
    public function show(RegulatedOrganization $regulatedOrganization): View
    {
        if (Route::currentRouteName() === locale().'.regulated-organizations.show-projects') {
            $regulatedOrganization->load('completedProjects', 'inProgressProjects', 'upcomingProjects');
        }

        $language = request()->query('language');

        if (! in_array($language, $regulatedOrganization->languages)) {
            $language = false;
        }

        return view('regulated-organizations.show', array_merge(compact('regulatedOrganization'), ['language' => $language ?? locale()]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegulatedOrganization $regulatedOrganization): View
    {
        return view('regulated-organizations.edit', [
            'regulatedOrganization' => $regulatedOrganization,
            'nullableRegions' => Options::forEnum(ProvinceOrTerritory::class)->nullable(__('Choose a province or territory…'))->toArray(),
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->toArray(),
            'sectors' => Options::forModels(Sector::class)->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegulatedOrganizationRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $data = $request->validated();

        $data = $request->validated();

        if (isset($data['accessibility_and_inclusion_links'])) {
            $data['accessibility_and_inclusion_links'] = array_filter(array_map('array_filter', $data['accessibility_and_inclusion_links']));
        }

        if (isset($data['social_links'])) {
            $data['social_links'] = array_filter($data['social_links']);
        }

        $regulatedOrganization->fill($data);

        $regulatedOrganization->save();

        $regulatedOrganization->sectors()->sync($data['sectors'] ?? []);

        flash(__('Your federally regulated organization has been updated.'), 'success');

        return $regulatedOrganization->handleUpdateRequest($request);
    }

    /**
     * Update the specified resource's status.
     */
    public function updatePublicationStatus(Request $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $regulatedOrganization->unpublish();
        } elseif ($request->input('publish')) {
            $regulatedOrganization->publish();
        }

        return redirect(localized_route('regulated-organizations.show', $regulatedOrganization));
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(RegulatedOrganization $regulatedOrganization): View
    {
        return view('regulated-organizations.delete', [
            'regulatedOrganization' => $regulatedOrganization,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRegulatedOrganizationRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $regulatedOrganization->delete();

        flash(__('Your federally regulated organization has been deleted.'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
