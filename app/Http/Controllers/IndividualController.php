<?php

namespace App\Http\Controllers;

use App\Enums\BaseDisabilityType;
use App\Enums\CommunityConnectorHasLivedExperience;
use App\Enums\ConsultingServices;
use App\Enums\MeetingTypes;
use App\Enums\ProvincesAndTerritories;
use App\Http\Requests\DestroyIndividualRequest;
use App\Http\Requests\SaveIndividualRolesRequest;
use App\Http\Requests\UpdateIndividualCommunicationAndMeetingPreferencesRequest;
use App\Http\Requests\UpdateIndividualConstituenciesRequest;
use App\Http\Requests\UpdateIndividualExperiencesRequest;
use App\Http\Requests\UpdateIndividualInterestsRequest;
use App\Http\Requests\UpdateIndividualRequest;
use App\Models\AccessSupport;
use App\Models\AgeBracket;
use App\Models\AreaType;
use App\Models\Constituency;
use App\Models\DisabilityType;
use App\Models\EthnoracialIdentity;
use App\Models\GenderIdentity;
use App\Models\Impact;
use App\Models\IndigenousIdentity;
use App\Models\Individual;
use App\Models\IndividualRole;
use App\Models\Language;
use App\Models\LivedExperience;
use App\Models\Sector;
use App\Statuses\IndividualStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelOptions\Options;

class IndividualController extends Controller
{
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
            'roles' => Options::forModels(IndividualRole::class)->toArray(),
        ]);
    }

    public function showRoleEdit(): View
    {
        $this->authorize('selectRole', Auth::user());

        $individual = Auth::user()->individual;

        return view('individuals.show-role-edit', [
            'individual' => $individual,
            'roles' => Options::forModels(IndividualRole::class)->toArray(),
            'selectedRoles' => $individual->individualRoles->pluck('id')->toArray(),
        ]);
    }

    public function saveRoles(SaveIndividualRolesRequest $request): RedirectResponse
    {
        $this->authorize('selectRole', Auth::user());

        $data = $request->validated();

        $individual = Auth::user()->individual;

        $individual->individualRoles()->sync($data['roles'] ?? []);

        if (! $individual->fresh()->isConsultant() && ! $individual->fresh()->isConnector()) {
            $individual->unpublish();
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
            // TODO: Is this the best way of handling these two constituencies?
            'transPeople' => Constituency::where('name_plural->en', 'Trans people')->first(),
            'twoslgbtqiaplusPeople' => Constituency::where('name_plural->en', '2SLGBTQIA+ people')->first(),
        ]));
    }

    public function edit(Individual $individual): View
    {
        $workingLanguages = [
            $individual->user->locale,
        ];

        if ($individual->user->signed_language) {
            $workingLanguages[] = $individual->user->signed_language;
        }

        return view('individuals.edit', [
            'individual' => $individual,
            'regions' => Options::forEnum(ProvincesAndTerritories::class)->nullable(__('Choose a province or territory…'))->toArray(),
            'sectors' => Options::forModels(Sector::class)->toArray(),
            'impacts' => Options::forModels(Impact::class)->toArray(),
            'constituencies' => Options::forModels(Constituency::class)->toArray(),
            'livedExperiences' => Options::forModels(LivedExperience::class)->toArray(),
            'ageBrackets' => Options::forModels(AgeBracket::class)->toArray(),
            'consultingServices' => Options::forEnum(ConsultingServices::class)->toArray(),
            'areaTypes' => Options::forModels(AreaType::class)->toArray(),
            'disabilityTypes' => Options::forModels(DisabilityType::query()->where('name->en', '!=', 'Cross-disability'))->toArray(),
            'crossDisability' => DisabilityType::query()->where('name->en', 'Cross-disability')->first(),
            'indigenousIdentities' => Options::forModels(IndigenousIdentity::class)->toArray(),
            'ethnoracialIdentities' => Options::forModels(EthnoracialIdentity::query()->where('name->en', '!=', 'White'))->toArray(),
            'women' => GenderIdentity::where('name_plural->en', 'Women')->first(),
            'transPeople' => Constituency::where('name_plural->en', 'Trans people')->first(),
            'twoslgbtqiaplusPeople' => Constituency::where('name_plural->en', '2SLGBTQIA+ people')->first(),
            'refugeesAndImmigrants' => Constituency::where('name_plural->en', 'Refugees and/or immigrants')->first(),
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'baseDisabilityTypes' => Options::forEnum(BaseDisabilityType::class)->toArray(),
            'refugeesAndImmigrantsOptions' => Options::forArray([
                '1' => __('Yes'),
                '0' => __('No'),
            ])->toArray(),
            'communityConnectorHasLivedExperience' => Options::forEnum(CommunityConnectorHasLivedExperience::class)->toArray(),
            'meetingTypes' => Options::forEnum(MeetingTypes::class)->toArray(),
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

        $individual->fill($data);

        $individual->save();

        return $individual->handleUpdateRequest($request, $individual->getStepForKey('about-you'));
    }

    public function updateConstituencies(UpdateIndividualConstituenciesRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        $data['constituencies'] = [];

        $crossDisability = DisabilityType::where('name->en', 'Cross-disability')->first();
        $refugeesAndImmigrants = Constituency::where('name->en', 'Refugee or immigrant')->first();

        if (isset($data['base_disability_type']) && $data['base_disability_type'] === 'cross_disability') {
            $data['disability_types'] = [
                $crossDisability->id,
            ];
            $data['other_disability'] = 0;
            $data['other_disability_type_connection'] = null;
        }

        if (! isset($data['other_disability']) || isset($data['base_disability_type']) && $data['base_disability_type'] == 'cross_disability') {
            $data['other_disability'] = 0;
            $data['other_disability_type_connection'] = null;
        }

        if (isset($data['refugees_and_immigrants']) && $data['refugees_and_immigrants'] == 1) {
            $individual->extra_attributes->has_refugee_and_immigrant_constituency = 1;

            $data['constituencies'][] = $refugeesAndImmigrants->id;
        } else {
            $individual->extra_attributes->has_refugee_and_immigrant_constituency = 0;
        }

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

            $individual->extra_attributes->has_gender_and_sexual_identities = 1;
        } else {
            $individual->extra_attributes->has_gender_and_sexual_identities = 0;
        }

        foreach ([
            'area_types' => 'areaTypeConnections',
            'lived_experiences' => 'livedExperienceConnections',
            'disability_types' => 'disabilityTypeConnections',
            'indigenous_identities' => 'indigenousIdentityConnections',
            'gender_identities' => 'genderIdentityConnections',
            'age_brackets' => 'ageBracketConnections',
            'ethnoracial_identities' => 'ethnoracialIdentityConnections',
            'constituencies' => 'constituencyConnections',
        ] as $relationship => $method) {
            if (isset($data[$relationship])) {
                $individual->$method()->sync($data[$relationship]);
            } else {
                $individual->$method()->detach();
            }
        }

        if (! isset($data['other_ethnoracial']) || $data['has_ethnoracial_identities'] == 0) {
            $data['other_ethnoracial'] = 0;
            $data['other_ethnoracial_identity_connection'] = null;
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
            $individual->languageConnections()->sync($languages);
        }

        foreach ([
            'indigenous_identities',
            'age_brackets',
            'ethnoracial_identities',
        ] as $relationship) {
            if (isset($data[$relationship])) {
                $individual->extra_attributes->set("has_$relationship", 1);
            } else {
                $individual->extra_attributes->set("has_$relationship", 0);
            }
        }

        $individual->fill($data);
        $individual->save();

        return $individual->handleUpdateRequest($request, $individual->getStepForKey('groups-you-can-connect-to'));
    }

    public function updateExperiences(UpdateIndividualExperiencesRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['relevant_experiences'])) {
            $relevant_experiences = array_filter(array_map('array_filter', $data['relevant_experiences']));
            if (empty($relevant_experiences)) {
                unset($data['relevant_experiences']);
            } else {
                $data['relevant_experiences'] = $relevant_experiences;
            }
        }

        $individual->fill($data);

        $individual->save();

        $individual->livedExperiences()->sync($data['lived_experiences'] ?? []);

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

    public function updateCommunicationAndMeetingPreferences(UpdateIndividualCommunicationAndMeetingPreferencesRequest $request, Individual $individual): RedirectResponse
    {
        $data = $request->validated();

        if ($data['preferred_contact_person'] === 'me') {
            $data['support_person_name'] = '';
            $data['support_person_email'] = '';
            $data['support_person_phone'] = '';
            $data['support_person_vrs'] = 0;
        }

        if ($data['preferred_contact_person'] === 'support-person') {
            $data['email'] = '';
            $data['phone'] = '';
            $data['vrs'] = 0;
        }

        $individual->fill($data);

        $individual->save();

        return $individual->handleUpdateRequest($request, $individual->getStepForKey('communication-and-meeting-preferences'));
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

    public function expressInterest(Request $request, Individual $individual): RedirectResponse
    {
        $request->validate([
            'project_id' => 'required|integer',
        ]);

        $individual->projectsOfInterest()->attach($request->input('project_id'));

        flash(__('You have expressed your interest in this project.'), 'success');

        return redirect()->back();
    }

    public function removeInterest(Request $request, Individual $individual): RedirectResponse
    {
        $request->validate([
            'project_id' => 'required|integer',
        ]);

        $individual->projectsOfInterest()->detach($request->input('project_id'));

        flash(__('You have removed your expression of interest in this project.'), 'success');

        return redirect()->back();
    }
}
