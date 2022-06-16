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
use App\Models\AgeBracket;
use App\Models\AreaType;
use App\Models\Constituency;
use App\Models\DisabilityType;
use App\Models\EthnoracialIdentity;
use App\Models\GenderIdentity;
use App\Models\IndigenousIdentity;
use App\Models\Language;
use App\Models\LivedExperience;
use App\Models\Organization;
use App\Models\OrganizationRole;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

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
            'consultingServices' => [
                'booking-providers' => __('consulting-services.booking-providers'),
                'planning-consultation' => __('consulting-services.planning-consultation'),
                'running-consultation' => __('consulting-services.running-consultation'),
                'analysis' => __('consulting-services.analysis'),
                'writing-reports' => __('consulting-services.writing-reports'),
            ],
            'languages' => ['' => __('Choose a language…')] + get_available_languages(true),
            'livedExperiences' => LivedExperience::all()->prepareForForm(),
            'crossDisability' => DisabilityType::where('name->en', 'Cross-disability')->first(),
            'disabilityTypes' => DisabilityType::where('name->en', '!=', 'Cross-disability')->get()->prepareForForm(),
            'indigenousIdentities' => IndigenousIdentity::all()->prepareForForm(),
            'areaTypes' => AreaType::all()->prepareForForm(),
            'women' => GenderIdentity::where('name_plural->en', 'Women')->first(),
            'transPeople' => Constituency::where('name_plural->en', 'Trans people')->first(),
            'twoslgbtqiaplusPeople' => Constituency::where('name_plural->en', '2SLGBTQIA+ people')->first(),
            'refugeesAndImmigrants' => Constituency::where('name_plural->en', 'Refugees and/or immigrants')->first(),
            'ageBrackets' => AgeBracket::all()->prepareForForm(),
            'ethnoracialIdentities' => EthnoracialIdentity::where('name->en', '!=', 'White')->get()->prepareForForm(),
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
        $data['constituencies'] = [];

        $crossDisability = DisabilityType::where('name->en', 'Cross-disability')->first();
        $refugeesAndImmigrants = Constituency::where('name->en', 'Refugee or immigrant')->first();

        if (isset($data['base_disability_type']) && $data['base_disability_type'] === 'cross_disability') {
            $data['disability_types'] = [
                $crossDisability->id,
            ];
            unset($data['other_disability_type']);
        }

        if (isset($data['refugees_and_immigrants']) && $data['refugees_and_immigrants'] == 1) {
            $organization->extra_attributes->has_refugee_and_immigrant_constituency = 1;

            $data['constituencies'][] = $refugeesAndImmigrants->id;
        } else {
            $organization->extra_attributes->has_refugee_and_immigrant_constituency = 0;
        }

        $data['other_disability_type'] = $data['other_disability_type'] ?? [];

        if (isset($data['gender_and_sexual_identities'])) {
            if (in_array('women', $data['gender_and_sexual_identities'])) {
                $women = GenderIdentity::where('name_plural->en', 'Women')->first();
                $data['gender_identities'][] = $women->id;
            }

            if (in_array('nb-gnc-fluid-people', $data['gender_and_sexual_identities'])) {
                $nb = GenderIdentity::where('name_plural->en', 'Non-binary people')->first();
                $data['gender_identities'][] = $nb->id;

                $gnc = GenderIdentity::where('name_plural->en', 'Gender non-conforming people')->first();
                $data['gender_identities'][] = $gnc->id;

                $fluid = GenderIdentity::where('name_plural->en', 'Gender fluid people')->first();
                $data['gender_identities'][] = $fluid->id;
            }

            if (in_array('trans-people', $data['gender_and_sexual_identities'])) {
                $transPeople = Constituency::where('name_plural->en', 'Trans people')->first();
                $data['constituencies'][] = $transPeople->id;
            }

            if (in_array('2slgbtqiaplus-people', $data['gender_and_sexual_identities'])) {
                $twoslgbtqiaplusPeople = Constituency::where('name_plural->en', '2SLGBTQIA+ people')->firstOrFail();
                $data['constituencies'][] = $twoslgbtqiaplusPeople->id;
            }

            $organization->extra_attributes->has_gender_and_sexual_identities = 1;
        } else {
            $organization->extra_attributes->has_gender_and_sexual_identities = 0;
        }

        foreach ([
            'area_types',
            'lived_experiences',
            'disability_types',
            'indigenous_identities',
            'gender_identities',
            'age_brackets',
            'ethnoracial_identities',
            'constituencies',
        ] as $relationship) {
            $method = Str::camel($relationship);
            if (isset($data[$relationship])) {
                $organization->$method()->sync($data[$relationship]);
            } else {
                $organization->$method()->detach();
            }
        }

        if (isset($data['constituent_languages'])) {
            $languages = [];
            foreach ($data['constituent_languages'] as $code) {
                $language = Language::firstOrCreate([
                    'code' => $code,
                    'name' => [
                        'en' => get_language_exonym($code, 'en'),
                        'fr' => get_language_exonym($code, 'fr'),
                    ],
                ]);
                $languages[] = $language->id;
            }
            $organization->constituentLanguages()->sync($languages);
        }

        foreach ([
            'indigenous_identities',
            'age_brackets',
            'ethnoracial_identities',
        ] as $relationship) {
            if (isset($data[$relationship])) {
                $organization->extra_attributes->set("has_{$relationship}", 1);
            } else {
                $organization->extra_attributes->set("has_{$relationship}", 0);
            }
        }

        $organization->fill($data);
        $organization->save();

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
