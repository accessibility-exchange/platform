<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyOrganizationRequest;
use App\Http\Requests\StoreOrganizationLanguagesRequest;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\StoreOrganizationRolesRequest;
use App\Http\Requests\StoreOrganizationTypeRequest;
use App\Http\Requests\UpdateOrganizationConstituenciesRequest;
use App\Http\Requests\UpdateOrganizationContactInformationRequest;
use App\Http\Requests\UpdateOrganizationInterestsRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\DisabilityType;
use App\Models\GenderIdentity;
use App\Models\IndigenousIdentity;
use App\Models\LivedExperience;
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

    public function create(): View
    {
        return view('organizations.create', [
            'type' => session()->get('type'),
        ]);
    }

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

        return redirect(localized_route('organizations.show-role-selection', $organization));
    }

    public function showRoleSelection(Organization $organization): View
    {
        return view('organizations.show-role-selection', [
            'organization' => $organization,
            'roles' => OrganizationRole::all()->prepareForForm(),
        ]);
    }

    public function storeRoles(StoreOrganizationRolesRequest $request, Organization $organization): RedirectResponse
    {
        $data = $request->validated();

        $organization->organizationRoles()->sync($data['roles'] ?? []);

        return redirect(localized_route('dashboard'));
    }

    public function showLanguageSelection(Organization $organization): View
    {
        return view('organizations.show-language-selection', [
            'organization' => $organization,
        ]);
    }

    public function storeLanguages(StoreOrganizationLanguagesRequest $request, Organization $organization): RedirectResponse
    {
        $organization->fill($request->validated());
        $organization->save();

        return redirect(localized_route('organizations.edit', $organization));
    }

    public function show(Organization $organization): View
    {
        return view('organizations.show', ['organization' => $organization]);
    }

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
            'livedExperiences' => LivedExperience::all()->prepareForForm(),
            'disabilityTypes' => DisabilityType::all()->prepareForForm(),
            'indigenousIdentities' => IndigenousIdentity::all()->prepareForForm(),
            'women' => GenderIdentity::where('name->en', 'Female')->first(),
            'nonBinary' => GenderIdentity::where('name->en', 'Non-binary')->first(),
            'genderNonConforming' => GenderIdentity::where('name->en', 'Gender non-conforming')->first(),
            'genderFluid' => GenderIdentity::where('name->en', 'Gender fluid')->first(),
        ]);
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $organization->fill($request->validated());
        $organization->save();

        return $organization->handleUpdateRequest($request, 1);
    }

    public function updateConstituencies(UpdateOrganizationConstituenciesRequest $request, Organization $organization): RedirectResponse
    {
        $data = $request->validated();


        if ($data['base_disability_type'] === 'cross_disability') {
            $data['cross_disability'] = true;
        } else {
            $data['cross_disability'] = null;
        }

        $data['trans_people'] = $request->has('trans_people');
        $data['twoslgbtqia'] = $request->has('twoslgbtqia');

        $organization->fill($data);
        $organization->save();

        if (isset($data['lived_experiences'])) {
            $organization->livedExperienceConstituencies()->sync($data['lived_experiences']);
        } else {
            $organization->livedExperienceConstituencies()->detach();
        }

        if (isset($data['disability_types'])) {
            $organization->disabilityConstituencies()->sync($data['disability_types']);
        } else {
            $organization->disabilityConstituencies()->detach();
        }

        if (isset($data['indigenous_identities'])) {
            $organization->indigenousConstituencies()->sync($data['indigenous_identities']);
        } else {
            $organization->indigenousConstituencies()->detach();
        }

        if (isset($data['gender_identities'])) {
            $organization->genderIdentityConstituencies()->sync($data['gender_identities']);
        } else {
            $organization->genderIdentityConstituencies()->detach();
        }

        return $organization->handleUpdateRequest($request, 2);
    }

    public function updateInterests(UpdateOrganizationInterestsRequest $request, Organization $organization): RedirectResponse
    {
        $organization->fill($request->validated());
        $organization->save();

        return $organization->handleUpdateRequest($request, 3);
    }

    public function updateContactInformation(UpdateOrganizationContactInformationRequest $request, Organization $organization): RedirectResponse
    {
        $organization->fill($request->validated());
        $organization->save();

        return $organization->handleUpdateRequest($request, 3);
    }

    public function destroy(DestroyOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $organization->delete();

        flash(__('organization.destroy_succeeded'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
