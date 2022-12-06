<?php

namespace App\Http\Controllers;

use App\Enums\BaseDisabilityType;
use App\Enums\ConsultingService;
use App\Enums\IdentityCluster;
use App\Enums\OrganizationRole;
use App\Enums\OrganizationType;
use App\Enums\ProvinceOrTerritory;
use App\Enums\StaffHaveLivedExperience;
use App\Enums\TeamRole;
use App\Http\Requests\DestroyOrganizationRequest;
use App\Http\Requests\SaveOrganizationRolesRequest;
use App\Http\Requests\StoreOrganizationLanguagesRequest;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\StoreOrganizationTypeRequest;
use App\Http\Requests\UpdateOrganizationConstituenciesRequest;
use App\Http\Requests\UpdateOrganizationContactInformationRequest;
use App\Http\Requests\UpdateOrganizationInterestsRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Language;
use App\Models\Organization;
use App\Models\Scopes\ReachableIdentityScope;
use App\Models\Sector;
use App\Statuses\OrganizationStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\LaravelOptions\Options;

class OrganizationController extends Controller
{
    public function index(): View
    {
        return view('organizations.index', ['organizations' => Organization::status(new OrganizationStatus('published'))->orderBy('name')->get()]);
    }

    public function showTypeSelection(): View
    {
        $this->authorize('create', Organization::class);

        return view('organizations.show-type-selection', [
            'types' => Options::forEnum(OrganizationType::class)->append(fn (OrganizationType $type) => [
                'label' => OrganizationType::pluralLabels()[$type->value],
                'hint' => $type->description(),
            ])->toArray(),
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
        $user = $request->user();
        $data = $request->validated();

        $data['contact_person_name'] = $user->name;
        $data['contact_person_email'] = $user->email;
        $data['preferred_contact_method'] = 'email';

        $data['working_languages'] = [$user->locale];

        $data['languages'] = config('locales.supported');

        $organization = Organization::create($data);

        session()->forget('type');

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
            'roles' => Options::forEnum(OrganizationRole::class)->append(fn (OrganizationRole $role) => [
                'hint' => $role->description(),
            ])->toArray(),
        ]);
    }

    public function showRoleEdit(Organization $organization): View
    {
        return view('organizations.show-role-edit', [
            'organization' => $organization,
            'roles' => Options::forEnum(OrganizationRole::class)->append(fn (OrganizationRole $role) => [
                'hint' => $role->description(),
            ])->toArray(),
        ]);
    }

    public function saveRoles(SaveOrganizationRolesRequest $request, Organization $organization): RedirectResponse
    {
        $organization->fill($request->validated());
        $organization->save();

        flash(__('Your roles have been saved.'), 'success');

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
        $language = request()->query('language');

        if (! in_array($language, $organization->languages)) {
            $language = false;
        }

        return view('organizations.show', array_merge(compact('organization'), [
            'language' => $language ?? locale(),
            // TODO: Is this the best way of handling these two constituencies?
            'transPeople' => Identity::where('name->en', 'Trans people')->first(),
            'twoslgbtqiaplusPeople' => Identity::where('name->en', '2SLGBTQIA+ people')->first(),
        ]));
    }

    public function edit(Organization $organization): View
    {
        return view('organizations.edit', [
            'organization' => $organization,
            'nullableRegions' => Options::forEnum(ProvinceOrTerritory::class)->nullable(__('Choose a province or territory…'))->toArray(),
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->toArray(),
            'roles' => Options::forEnum(TeamRole::class)->toArray(),
            'consultingServices' => Options::forEnum(ConsultingService::class)->toArray(),
            'sectors' => Options::forModels(Sector::class)->toArray(),
            'impacts' => Options::forModels(Impact::class)->toArray(),
            'ageBrackets' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Age))->toArray(),
            'areaTypes' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Area))->toArray(),
            'baseDisabilityTypes' => Options::forEnum(BaseDisabilityType::class)->toArray(),
            'disabilityTypes' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf))->toArray(),
            'ethnoracialIdentities' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Ethnoracial))->reject(fn (Identity $identity) => $identity->name === __('White'))->toArray(),
            'genderAndSexualIdentities' => array_merge(Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Gender)->whereNot(function ($query) {
                $query->whereJsonContains('clusters', IdentityCluster::GenderDiverse);
            }))->toArray(), Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->whereNot(function ($query) {
                $query->whereJsonContains('clusters', IdentityCluster::Gender);
            }))->toArray()),
            'indigenousIdentities' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Indigenous))->toArray(),
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'livedExperiences' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::LivedExperience)->withoutGlobalScope(ReachableIdentityScope::class))->toArray(),
            'refugeesAndImmigrantsOptions' => Options::forArray([
                '1' => __('Yes'),
                '0' => __('No'),
            ])->toArray(),
            'staffHaveLivedExperience' => Options::forEnum(StaffHaveLivedExperience::class)->toArray(),
        ]);
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['working_languages'])) {
            $data['working_languages'] = array_filter($data['working_languages']);
        }

        if (isset($data['social_links'])) {
            $data['social_links'] = array_filter($data['social_links']);
        }

        $organization->fill($data);
        $organization->save();

        return $organization->handleUpdateRequest($request, 1);
    }

    public function updateConstituencies(UpdateOrganizationConstituenciesRequest $request, Organization $organization): RedirectResponse
    {
        $data = $request->validated();

        $data['identities'] = [];

        $organization->extra_attributes->set('disability_and_deaf_constituencies', $data['disability_and_deaf']);

        if ($data['base_disability_type'] === 'cross_disability_and_deaf') {
            $organization->extra_attributes->set('cross_disability_and_deaf_constituencies', 1);
            $data['has_other_disability_constituency'] = 0;
            $data['other_disability_constituency'] = null;
        } else {
            $organization->extra_attributes->set('cross_disability_and_deaf_constituencies', 0);
        }

        if (isset($data['refugees_and_immigrants']) && $data['refugees_and_immigrants'] == 1) {
            foreach (Identity::whereJsonContains('clusters', IdentityCluster::Status)->get() as $statusIdentity) {
                $data['identities'][] = $statusIdentity->id;
            }
        }

        if (isset($data['nb_gnc_fluid_identity']) && $data['nb_gnc_fluid_identity'] == 1) {
            foreach (Identity::whereJsonContains('clusters', IdentityCluster::GenderDiverse)->get() as $genderDiverseIdentity) {
                $data['identities'][] = $genderDiverseIdentity->id;
            }
        }

        foreach ([
            'age_bracket_constituencies',
            'area_type_constituencies',
            'disability_and_deaf_constituencies',
            'ethnoracial_identity_constituencies',
            'gender_and_sexuality_constituencies',
            'indigenous_constituencies',
            'lived_experience_constituencies',
        ] as $relationship) {
            if (isset($data[$relationship])) {
                foreach ($data[$relationship] as $identity) {
                    $data['identities'][] = $identity;
                }
            }
        }

        $organization->constituentIdentities()->sync($data['identities']);

        if ($data['has_ethnoracial_identity_constituencies'] == 0) {
            $data['other_ethnoracial_identity_constituency'] = null;
        }

        if (isset($data['language_constituencies'])) {
            $languages = [];
            foreach ($data['language_constituencies'] as $code) {
                $language = Language::firstOrCreate(
                    ['code' => $code],
                    [
                        'name' => [
                            'en' => get_language_exonym($code, 'en'),
                            'asl' => get_language_exonym($code, 'en'),
                            'fr' => get_language_exonym($code, 'fr'),
                            'lsq' => get_language_exonym($code, 'fr'),
                        ],
                    ],
                );
                $languages[] = $language->id;
            }
            $organization->languageConstituencies()->sync($languages);
        }

        $organization->fill($data);
        $organization->save();

        return $organization->handleUpdateRequest($request, 2);
    }

    public function updateInterests(UpdateOrganizationInterestsRequest $request, Organization $organization): RedirectResponse
    {
        $data = $request->validated();

        foreach ([
            'sectors',
            'impacts',
        ] as $relationship) {
            $method = Str::camel($relationship);
            if (isset($data[$relationship])) {
                $organization->$method()->sync($data[$relationship]);
            } else {
                $organization->$method()->detach();
            }
        }

        return $organization->handleUpdateRequest($request, 3);
    }

    public function updateContactInformation(UpdateOrganizationContactInformationRequest $request, Organization $organization): RedirectResponse
    {
        $data = $request->validated();

        $organization->fill($data);
        $organization->save();

        return $organization->handleUpdateRequest($request, 4);
    }

    public function updatePublicationStatus(Request $request, Organization $organization): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $organization->unpublish();
        } elseif ($request->input('publish')) {
            $organization->publish();
        }

        return redirect(localized_route('organizations.show', $organization));
    }

    public function destroy(DestroyOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $organization->delete();

        flash(__('organization.destroy_succeeded'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
