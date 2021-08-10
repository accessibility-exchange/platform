<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrganizationRequest;
use App\Http\Requests\DestroyOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('organizations.index', ['organizations' => Organization::orderBy('name')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Organization::class);

        return view('organizations.create', [
            'regions' => get_regions(['CA'], locale()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateOrganizationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateOrganizationRequest $request)
    {
        $organization = Organization::create($request->validated());

        $organization->users()->attach(
            $request->user(),
            ['role' => 'admin']
        );

        flash(__('organization.create_succeeded'), 'success');

        return redirect(localized_route('organizations.show', $organization));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\View\View
     */
    public function show(Organization $organization)
    {
        return view('organizations.show', ['organization' => $organization]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\View\View
     */
    public function edit(Organization $organization)
    {
        $roles = [];

        foreach (config('hearth.organizations.roles') as $role) {
            $roles[$role] = __('roles.' . $role);
        }

        return view('organizations.edit', [
            'organization' => $organization,
            'regions' => get_regions(['CA'], locale()),
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrganizationRequest  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $organization->fill($request->validated());
        $organization->save();

        flash(__('organization.update_succeeded'), 'success');

        return redirect(localized_route('organizations.show', $organization));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DestroyOrganizationRequest  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyOrganizationRequest $request, Organization $organization)
    {
        $organization->delete();

        flash(__('organization.destroy_succeeded'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
