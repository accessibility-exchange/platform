<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyOrganizationRequest;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('organizations.index', ['organizations' => Organization::orderBy('name')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('organizations.create', [
            'regions' => get_regions(['CA'], locale()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrganizationRequest $request
     * @return RedirectResponse
     */
    public function store(StoreOrganizationRequest $request): RedirectResponse
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
     * @param Organization $organization
     * @return View
     */
    public function show(Organization $organization): View
    {
        return view('organizations.show', ['organization' => $organization]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Organization $organization
     * @return View
     */
    public function edit(Organization $organization): View
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
     * @param UpdateOrganizationRequest $request
     * @param Organization $organization
     * @return RedirectResponse
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $organization->fill($request->validated());
        $organization->save();

        flash(__('organization.update_succeeded'), 'success');

        return redirect(localized_route('organizations.show', $organization));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyOrganizationRequest $request
     * @param Organization $organization
     * @return RedirectResponse
     */
    public function destroy(DestroyOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $organization->delete();

        flash(__('organization.destroy_succeeded'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
