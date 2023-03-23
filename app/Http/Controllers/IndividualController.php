<?php

namespace App\Http\Controllers;

use App\Enums\BaseDisabilityType;
use App\Enums\CommunityConnectorHasLivedExperience;
use App\Enums\ConsultingService;
use App\Enums\ContactPerson;
use App\Enums\IdentityCluster;
use App\Enums\IndividualRole;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use App\Http\Requests\DestroyIndividualRequest;
use App\Http\Requests\SaveIndividualRolesRequest;
use App\Http\Requests\UpdateIndividualCommunicationAndConsultationPreferencesRequest;
use App\Http\Requests\UpdateIndividualConstituenciesRequest;
use App\Http\Requests\UpdateIndividualExperiencesRequest;
use App\Http\Requests\UpdateIndividualInterestsRequest;
use App\Http\Requests\UpdateIndividualRequest;
use App\Models\AccessSupport;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\Language;
use App\Models\Scopes\ReachableIdentityScope;
use App\Models\Sector;
use App\Statuses\IndividualStatus;
use App\Traits\UserEmailVerification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelOptions\Options;

class IndividualController extends Controller
{
    use UserEmailVerification;

    public function index(): View
    {
        return view('individuals.index', [
            'individuals' => Individual::status(new IndividualStatus('published'))->orderBy('name')->get(),
        ]);
    }

    public function showRoleSelection(): View
    {
        $this->authorize('selectRole', Auth::user());

        return view('individuals.show-role-selection', [
            'individual' => Auth::user()->individual,
            'roles' => Options::forEnum(IndividualRole::class)->append(fn (IndividualRole $role) => [
                'hint' => $role->description(),
            ])->toArray(),
        ]);
    }

    public function showRoleEdit(): View
    {
        $this->authorize('selectRole', Auth::user());

        $individual = Auth::user()->individual;

        return view('individuals.show-role-edit', [
            'individual' => $individual,
            'roles' => Options::forEnum(IndividualRole::class)->append(fn (IndividualRole $role) => [
                'hint' => $role->description(),
            ])->toArray(),
        ]);
    }

    public function saveRoles(SaveIndividualRolesRequest $request): RedirectResponse
    {
        $this->authorize('selectRole', Auth::user());

        $data = $request->validated();

        $individual = Auth::user()->individual;
        $oldRoles = $individual->roles ?? [];

        $individual->fill($data);
        $individual->save();

        $newRoles = $individual->fresh()->roles;

        $connectorRole = IndividualRole::CommunityConnector->value;
        $consultantRole = IndividualRole::AccessibilityConsultant->value;

        if ((in_array($connectorRole, $oldRoles) || in_array($consultantRole, $oldRoles)) && ! in_array($connectorRole, $newRoles) && ! in_array($consultantRole, $newRoles)) {
            $individual->unpublish(true);
            flash(__('Your roles have been saved.'), 'success');
        } elseif (count($oldRoles) && ((! in_array($consultantRole, $oldRoles) && in_array($consultantRole, $newRoles)) || (! in_array($connectorRole, $oldRoles) && in_array($connectorRole, $newRoles)))) {
            flash(__('Your roles have been saved.').' '.__('Please review your page. There is some information for your new role that you will have to fill in.').' <a href="'.localized_route('individuals.edit', $individual).'">'.__('Review page').'</a>', 'warning');
        } else {
            flash(__('Your roles have been saved.'), 'success');
        }

        return redirect(localized_route('dashboard'));
    }

    public function show(Individual $individual): View
    {
        $language = request()->query('language');

        if (! in_array($language, $individual->languages)) {
            $language = false;
        }

        return view('individuals.show', array_merge(compact('individual'), [
            'language' => $language ?? locale(),
        ]));
    }

    public function edit(Individual $individual): View
    {
        $workingLanguages = [
            $individual->user->locale,
        ];

        return view('individuals.edit', [
            'individual' => $individual,
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->nullable(__('Choose a province or territory…'))->toArray(),
            'sectors' => Options::forModels(Sector::class)->toArray(),
            'impacts' => Options::forModels(Impact::class)->toArray(),
            'consultingServices' => Options::forEnum(ConsultingService::class)->toArray(),
            'ageBrackets' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Age))->toArray(),
            'areaTypes' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Area))->toArray(),
            'baseDisabilityTypes' => Options::forEnum(BaseDisabilityType::class)->toArray(),
            'disabilityTypes' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf))->toArray(),
            'ethnoracialIdentities' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Ethnoracial))->toArray(),
            'genderAndSexualIdentities' => array_merge(Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Gender)->whereNot(function ($query) {
                $query->whereJsonContains('clusters', IdentityCluster::GenderDiverse);
            }))->toArray(), Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->whereNot(function ($query) {
                $query->whereJsonContains('clusters', IdentityCluster::Gender);
            }))->toArray()),
            'indigenousIdentities' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Indigenous))->toArray(),
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'livedExperiences' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::LivedExperience)->withoutGlobalScope(ReachableIdentityScope::class))->toArray(),
            'yesNoOptions' => Options::forArray([
                '1' => __('Yes'),
                '0' => __('No'),
            ])->toArray(),
            'communityConnectorHasLivedExperience' => Options::forEnum(CommunityConnectorHasLivedExperience::class)->toArray(),
            'contactPeople' => Options::forEnum(ContactPerson::class)->toArray(),
            'meetingTypes' => Options::forEnum(MeetingType::class)->toArray(),
            'accessNeeds' => Options::forModels(AccessSupport::class)->toArray(),
            'workingLanguages' => $workingLanguages,
        ]);
    }

    public function update(UpdateIndividualRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['working_languages'])) {
            $data['working_languages'] = array_filter($data['working_languages']);
        }

        if (isset($data['social_links'])) {
            $data['social_links'] = array_filter($data['social_links']);
        }

        $individual->fill($data);
        $individual->save();

        return $individual->handleUpdateRequest($request, $individual->getStepForKey('about-you'));
    }

    public function updateConstituencies(UpdateIndividualConstituenciesRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        $data['identities'] = [];

        if (isset($data['base_disability_type'])) {
            $individual->extra_attributes->set('disability_and_deaf_connections', $data['disability_and_deaf']);
        } else {
            $individual->extra_attributes->forget('disability_and_deaf_connections');
        }

        if (isset($data['base_disability_type'])) {
            if ($data['base_disability_type'] === 'cross_disability_and_deaf') {
                $individual->extra_attributes->set('cross_disability_and_deaf_connections', 1);
                $data['has_other_disability_connection'] = 0;
                $data['other_disability_connection'] = null;
            } else {
                $individual->extra_attributes->set('cross_disability_and_deaf_connections', 0);
            }
        } else {
            $individual->extra_attributes->forget('cross_disability_and_deaf_connections');
        }

        if (! isset($data['has_other_disability_connection']) || isset($data['base_disability_type']) && $data['base_disability_type'] == 'cross_disability_and_deaf') {
            $data['has_other_disability_connection'] = 0;
            $data['other_disability_connection'] = null;
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
            'age_bracket_connections',
            'area_type_connections',
            'disability_and_deaf_connections',
            'ethnoracial_identity_connections',
            'gender_and_sexuality_connections',
            'indigenous_connections',
            'lived_experience_connections',
        ] as $relationship) {
            if (isset($data[$relationship])) {
                foreach ($data[$relationship] as $identity) {
                    $data['identities'][] = $identity;
                }
            }
        }

        $individual->identityConnections()->sync($data['identities']);

        if ($data['has_ethnoracial_identity_connections'] == 0 || $data['has_other_ethnoracial_identity_connection'] == 0) {
            $data['other_ethnoracial_identity_connection'] = null;
        }

        if (isset($data['language_connections'])) {
            $languages = [];
            foreach ($data['language_connections'] as $code) {
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
            $individual->languageConnections()->sync($languages);
        }

        $individual->fill($data);
        $individual->save();

        return $individual->handleUpdateRequest($request, $individual->getStepForKey('groups-you-can-connect-to'));
    }

    public function updateExperiences(UpdateIndividualExperiencesRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['relevant_experiences'])) {
            $data['relevant_experiences'] = array_filter(array_map('array_filter', $data['relevant_experiences']));
        } else {
            $individual->relevant_experiences = [];
        }

        $individual->fill($data);

        $individual->save();

        return $individual->handleUpdateRequest($request, $individual->getStepForKey('experiences'));
    }

    public function updateInterests(UpdateIndividualInterestsRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        $individual->fill($data);

        $individual->save();

        $individual->sectorsOfInterest()->sync($data['sectors'] ?? []);
        $individual->impactsOfInterest()->sync($data['impacts'] ?? []);

        return $individual->handleUpdateRequest($request, $individual->getStepForKey('interests'));
    }

    public function updateCommunicationAndConsultationPreferences(UpdateIndividualCommunicationAndConsultationPreferencesRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        if ($data['preferred_contact_person'] === 'me') {
            $data['support_person_name'] = '';
            $data['support_person_email'] = '';
            $data['support_person_phone'] = '';
            $data['support_person_vrs'] = 0;
        }

        if ($data['preferred_contact_person'] === 'support-person') {
            $data['phone'] = '';
            $data['vrs'] = 0;
        }

        $user = Auth::user();
        $individual = $user->individual;

        if (
            $data['email'] !== ''
                && $data['email'] !== $user->email
                && $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $data['email']);
        }

        $user->fill($data);
        $user->save();

        $individual->fill($data);
        $individual->save();

        return $user->individual->handleUpdateRequest($request, $user->individual->getStepForKey('communication-and-consultation-preferences'));
    }

    public function updatePublicationStatus(Request $request, Individual $individual): RedirectResponse
    {
        if ($request->input('unpublish')) {
            $individual->unpublish();
        } elseif ($request->input('publish')) {
            $individual->publish();
        }

        return redirect(localized_route('individuals.show', $individual));
    }

    public function destroy(DestroyIndividualRequest $request, Individual $individual): RedirectResponse
    {
        $request->validated();

        $individual->delete();

        flash(__('Your individual page has been deleted.'), 'success');

        return redirect(localized_route('dashboard'));
    }
}
