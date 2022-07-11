<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyRegulatedOrganizationRequest;
use App\Http\Requests\StoreRegulatedOrganizationLanguagesRequest;
use App\Http\Requests\StoreRegulatedOrganizationRequest;
use App\Http\Requests\StoreRegulatedOrganizationTypeRequest;
use App\Http\Requests\UpdateRegulatedOrganizationRequest;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\Sector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelOptions\Options;

class RegulatedOrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('regulated-organizations.index', ['regulatedOrganizations' => RegulatedOrganization::orderBy('name')->get()]);
    }

    /**
     * Show a type selection page for the regulated organization.
     *
     * @return View
     */
    public function showTypeSelection(): View
    {
        return view('regulated-organizations.show-type-selection', [
            'types' => [
                'government' => __('Government'),
                'business' => __('Business'),
                'public-sector' => [
                    'label' => __('Other public sector organization'),
                    'hint' => __('That is regulated by the Accessible Canada Act'),
                ],
            ],
        ]);
    }

    /**
     * Store the regulated organization's name in the session.
     *
     * @param  StoreRegulatedOrganizationTypeRequest  $request
     * @return RedirectResponse
     */
    public function storeType(StoreRegulatedOrganizationTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('type', $data['type']);

        return redirect(localized_route('regulated-organizations.create'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('regulated-organizations.create-initial', [
            'type' => session()->get('type'),
        ]);
    }

    /**
     * Store the model.
     *
     * @param  StoreRegulatedOrganizationRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreRegulatedOrganizationRequest $request): RedirectResponse
    {
        $regulatedOrganization = RegulatedOrganization::create($request->validated());

        session()->forget('type');

        $regulatedOrganization->users()->attach(
            $request->user(),
            ['role' => 'admin']
        );

        return redirect(localized_route('dashboard'));
    }

    /**
     * Show a language selection page for the logged-in user.
     *
     * @param  RegulatedOrganization  $regulatedOrganization
     * @return View
     */
    public function showLanguageSelection(RegulatedOrganization $regulatedOrganization): View
    {
        return view('regulated-organizations.show-language-selection', [
            'regulatedOrganization' => $regulatedOrganization,
        ]);
    }

    /**
     * Update the languages of a resource.
     *
     * @param  StoreRegulatedOrganizationLanguagesRequest  $request
     * @param  RegulatedOrganization  $regulatedOrganization
     * @return RedirectResponse
     */
    public function storeLanguages(StoreRegulatedOrganizationLanguagesRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $regulatedOrganization->fill($request->validated());
        $regulatedOrganization->save();

        return redirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    /**
     * Display the specified resource.
     *
     * @param  RegulatedOrganization  $regulatedOrganization
     * @return View
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
     *
     * @param  RegulatedOrganization  $regulatedOrganization
     * @return View
     */
    public function edit(RegulatedOrganization $regulatedOrganization): View
    {
        return view('regulated-organizations.edit', [
            'regulatedOrganization' => $regulatedOrganization,
            'regions' => get_regions(['CA'], locale()),
            'sectors' => Options::forModels(Sector::class)->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRegulatedOrganizationRequest  $request
     * @param  RegulatedOrganization  $regulatedOrganization
     * @return RedirectResponse
     */
    public function update(UpdateRegulatedOrganizationRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $data = $request->validated();

        $regulatedOrganization->fill($data);

        $regulatedOrganization->save();

        $regulatedOrganization->sectors()->sync($data['sectors'] ?? []);

        flash(__('Your federally regulated organization has been updated.'), 'success');

        return $regulatedOrganization->handleUpdateRequest($request);
    }

    /**
     * Update the specified resource's status.
     *
     * @param  Request  $request
     * @param  RegulatedOrganization  $regulatedOrganization
     * @return RedirectResponse
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
     *
     * @param  RegulatedOrganization  $regulatedOrganization
     * @return View
     */
    public function delete(RegulatedOrganization $regulatedOrganization): View
    {
        return view('regulated-organizations.delete', [
            'regulatedOrganization' => $regulatedOrganization,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyRegulatedOrganizationRequest  $request
     * @param  RegulatedOrganization  $regulatedOrganization
     * @return RedirectResponse
     */
    public function destroy(DestroyRegulatedOrganizationRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $regulatedOrganization->delete();

        flash(__('Your federally regulated organization has been deleted.'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
