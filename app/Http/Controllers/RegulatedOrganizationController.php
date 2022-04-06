<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyRegulatedOrganizationRequest;
use App\Http\Requests\StoreRegulatedOrganizationRequest;
use App\Http\Requests\UpdateRegulatedOrganizationRequest;
use App\Models\RegulatedOrganization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class RegulatedOrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('regulated-organizations.index', ['regulatedOrganizations' => RegulatedOrganization::orderBy('name')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create', RegulatedOrganization::class);

        return view('regulated-organizations.create', [
            'regions' => get_regions(['CA'], \locale()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRegulatedOrganizationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRegulatedOrganizationRequest $request): RedirectResponse
    {
        $regulatedOrganization = RegulatedOrganization::create($request->validated());

        $regulatedOrganization->users()->attach(
            $request->user(),
            ['role' => 'admin']
        );

        flash(__('Your federally regulated organization has been created.'), 'success');


        return redirect(\localized_route('regulated-organizations.show', $regulatedOrganization));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RegulatedOrganization  $regulatedOrganization
     * @return \Illuminate\View\View
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
     * @param  \App\Models\RegulatedOrganization  $regulatedOrganization
     * @return \Illuminate\View\View
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
     * @param  \App\Http\Requests\UpdateRegulatedOrganizationRequest  $request
     * @param  \App\Models\RegulatedOrganization  $regulatedOrganization
     * @return \Illuminate\Http\RedirectResponse
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
     * @param  \App\Http\Requests\DestroyRegulatedOrganizationRequest  $request
     * @param  \App\Models\RegulatedOrganization  $regulatedOrganization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyRegulatedOrganizationRequest $request, RegulatedOrganization $regulatedOrganization): RedirectResponse
    {
        $regulatedOrganization->delete();

        flash(__('Your federally regulated organization has been deleted.'), 'success');

        return redirect(\localized_route('dashboard'));
    }
}
