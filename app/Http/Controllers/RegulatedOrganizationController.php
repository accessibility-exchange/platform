<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyRegulatedOrganizationRequest;
use App\Http\Requests\StoreRegulatedOrganizationRequest;
use App\Http\Requests\StoreRegulatedOrganizationTypeRequest;
use App\Http\Requests\UpdateRegulatedOrganizationRequest;
use App\Models\RegulatedOrganization;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

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
     * Show the form for finding or creating a new resource.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function findOrCreate(): View
    {
        $this->authorize('create', RegulatedOrganization::class);

        return view('regulated-organizations.find-or-create');
    }

    /**
     * Show a role selection page for the logged-in user.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function showTypeSelection(): View
    {
        $this->authorize('create', RegulatedOrganization::class);

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
     * @param StoreRegulatedOrganizationTypeRequest $request
     * @return RedirectResponse
     */
    public function storeType(StoreRegulatedOrganizationTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('type', $data['type']);

        return redirect(\localized_route('regulated-organizations.create', ['step' => 1]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', RegulatedOrganization::class);

        return view('regulated-organizations.create-initial', [
            'type' => session()->get('type'),
        ]);
    }

    /**
     * Store the regulated organization's name in the session.
     *
     * @param StoreRegulatedOrganizationRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRegulatedOrganizationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $regulatedOrganization = RegulatedOrganization::create($data);

        session()->forget('type');

        return redirect(\localized_route('regulated-organizations.create', ['step' => 2]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRegulatedOrganizationRequest $request
     * @return RedirectResponse
     */
    public function storeLanguages(StoreRegulatedOrganizationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['name'] = session()->get('name');

        $regulatedOrganization = RegulatedOrganization::create($data);

        session()->forget('name');

        $regulatedOrganization->users()->attach(
            $request->user(),
            ['role' => 'admin']
        );

        return redirect(\localized_route('regulated-organizations.show', $regulatedOrganization));
    }

    /**
     * Display the specified resource.
     *
     * @param RegulatedOrganization $regulatedOrganization
     * @return View
     */
    public function show(RegulatedOrganization $regulatedOrganization): View
    {
        if (Route::currentRouteName() === \locale() . '.regulated-organizations.show') {
            $regulatedOrganization->load('currentProjects');
        } elseif (Route::currentRouteName() === \locale() . '.regulated-organizations.show-projects') {
            $regulatedOrganization->load('pastProjects', 'currentProjects');
        }

        return view('regulated-organizations.show', compact('regulatedOrganization'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param RegulatedOrganization $regulatedOrganization
     * @return View
     */
    public function edit(RegulatedOrganization $regulatedOrganization): View
    {
        $roles = [];

        foreach (config('hearth.organizations.roles') as $role) {
            $roles[$role] = __('roles.' . $role);
        }

        return view('regulated-organizations.edit', [
            'regulatedOrganization' => $regulatedOrganization,
            'regions' => get_regions(['CA'], \locale()),
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRegulatedOrganizationRequest $request
     * @param RegulatedOrganization $regulatedOrganization
     * @return RedirectResponse
     */
    public function update(UpdateRegulatedOrganizationRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $regulatedOrganization->fill($request->validated());
        $regulatedOrganization->save();

        flash(__('Your federally regulated organization has been updated.'), 'success');

        return redirect(\localized_route('regulated-organizations.show', $regulatedOrganization));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyRegulatedOrganizationRequest $request
     * @param RegulatedOrganization $regulatedOrganization
     * @return RedirectResponse
     */
    public function destroy(DestroyRegulatedOrganizationRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $regulatedOrganization->delete();

        flash(__('Your federally regulated organization has been deleted.'), 'success');

        return redirect(\localized_route('dashboard'));
    }
}
