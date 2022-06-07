<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyOrganizationRequest;
use App\Http\Requests\StoreOrganizationLanguagesRequest;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\StoreOrganizationTypeRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use App\Models\OrganizationRole;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrganizationController extends Controller
{
    public function index(): View
    {
        return view('organizations.index', ['organizations' => Organization::orderBy('name')->get()]);
    }

    /**
     * Store the organization's name in the session.
     */
    public function showTypeSelection(): View
    {
        $this->authorize('create', Organization::class);

        return view('organizations.show-type-selection', [
            'types' => [
                'representative' => [
                    'label' => __('Representative organization'),
                    'hint' => __('Organizations “of” disability, Deaf, and family-based organizations. Constituted primarily by people with disabilities.'),
                ],
                'support' => [
                    'label' => __('Support organization'),
                    'hint' => __('Organizations that provide support “for” disability, Deaf, and family-based members. Not constituted primarily by people with disabilities.'),
                ],
                'civil-society' => [
                    'label' => __('Broader civil society organization'),
                    'hint' => __('Organizations which have some constituency of persons with disabilities, Deaf persons, or family members, but these groups are not their primary mandate. Groups served, for example, can include: Indigenous organizations, 2SLGBTQ+ organizations, immigrant and refugee groups, and women’s groups.'),
                ],
            ],
        ]);
    }

    public function storeType(StoreOrganizationTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('type', $data['type']);

        return redirect(localized_route('organizations.create'));
    }

    public function showRoleSelection(): View
    {
        $organizationRoles = OrganizationRole::all();

        $roles = [];

        foreach ($organizationRoles as $role) {
            $roles[$role->id] = [
                'label' => $role->name,
                'hint' => $role->description,
            ];
        }

        return view('organizations.show-role-selection', [
            'roles' => $roles,
        ]);
    }

    public function saveRole(\App\Http\Requests\StoreOrganizationRoleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('roles', $data['roles']);

        return redirect(localized_route('dashboard'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('organizations.create-initial', [
            'type' => session()->get('type'),
            'roles' => session()->get('roles'),
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
        $data = $request->validated();

        $organization = Organization::create($data);

        session()->forget('type');
        session()->forget('roles');

        $organization->users()->attach(
            $request->user(),
            ['role' => 'admin']
        );

        $organization->organizationRoles()->sync($data['roles'] ?? []);

        return redirect(localized_route('dashboard'));
    }

    public function showLanguageSelection(Organization $organization): View
    {
        return view('organizations.show-language-selection', [
            'organization' => $organization,
        ]);
    }

    /**
     * Update the languages of a resource.
     *
     * @param StoreOrganizationLanguagesRequest $request
     * @param Organization $organization
     * @return RedirectResponse
     */
    public function storeLanguages(StoreOrganizationLanguagesRequest $request, Organization $organization): RedirectResponse
    {
        $organization->fill($request->validated());
        $organization->save();

        return redirect(localized_route('organizations.edit', $organization));
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
            'areaTypes' => [
                'urban' => __('organization.area_types.urban'),
                'rural' => __('organization.area_types.rural'),
                'remote' => __('organization.area_types.remote'),
            ],
            'consultingServices' => [
                'booking-providers' => __('consulting-services.booking-providers'),
                'planning-consultation' => __('consulting-services.planning-consultation'),
                'running-consultation' => __('consulting-services.running-consultation'),
                'analysis' => __('consulting-services.analysis'),
                'writing-reports' => __('consulting-services.writing-reports'),
            ],
            'languages' => ['' => __('Choose a language…')] + get_available_languages(true),
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

        return $organization->handleUpdateRequest($request, 1);
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
