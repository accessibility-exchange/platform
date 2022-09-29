<?php

namespace App\Http\Controllers;

use App\Enums\Availability;
use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use App\Enums\TimeZone;
use App\Enums\Weekday;
use App\Http\Requests\StoreEngagementFormatRequest;
use App\Http\Requests\StoreEngagementLanguagesRequest;
use App\Http\Requests\StoreEngagementRecruitmentRequest;
use App\Http\Requests\StoreEngagementRequest;
use App\Http\Requests\UpdateEngagementLanguagesRequest;
use App\Http\Requests\UpdateEngagementRequest;
use App\Http\Requests\UpdateEngagementSelectionCriteriaRequest;
use App\Models\AgeBracket;
use App\Models\AreaType;
use App\Models\Constituency;
use App\Models\DisabilityType;
use App\Models\Engagement;
use App\Models\EthnoracialIdentity;
use App\Models\GenderIdentity;
use App\Models\IndigenousIdentity;
use App\Models\Language;
use App\Models\MatchingStrategy;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Spatie\LaravelOptions\Options;

class EngagementController extends Controller
{
    public function showLanguageSelection(Project $project): View
    {
        return view('engagements.show-language-selection', [
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'project' => $project,
        ]);
    }

    public function storeLanguages(StoreEngagementLanguagesRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        session()->put('languages', $data['languages']);

        return redirect(localized_route('engagements.create', $project));
    }

    public function create(Project $project): View
    {
        return view('engagements.create', [
            'project' => $project,
        ]);
    }

    public function store(StoreEngagementRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        $data['languages'] = session('languages', $project->languages);

        $engagement = Engagement::create($data);

        $matchingStrategy = new MatchingStrategy();

        $engagement->matchingStrategy()->save($matchingStrategy);

        session()->forget('languages');

        flash(__('Your engagement has been created.'), 'success');

        $redirect = match ($engagement->who) {
            'organization' => localized_route('engagements.manage', $engagement),
            default => localized_route('engagements.show-format-selection', $engagement),
        };

        return redirect($redirect);
    }

    public function showFormatSelection(Engagement $engagement): View
    {
        return view('engagements.show-format-selection', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'formats' => Options::forEnum(EngagementFormat::class)->toArray(),
        ]);
    }

    public function storeFormat(StoreEngagementFormatRequest $request, Engagement $engagement): RedirectResponse
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        $engagement = $engagement->fresh();

        return redirect(localized_route('engagements.show-recruitment-selection', $engagement));
    }

    public function showRecruitmentSelection(Engagement $engagement): View
    {
        return view('engagements.show-recruitment-selection', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'recruitments' => Options::forEnum(EngagementRecruitment::class)->append(fn (EngagementRecruitment $engagementRecruitment) => [
                'hint' => $engagementRecruitment === EngagementRecruitment::CommunityConnector ?
                    __('Hire a Community Connector (who can be an individual or a Community Organization) to recruit people manually from within their networks. This option is best if you are looking for a specific or hard-to-reach group.') :
                    __('Post your engagement as an open call. Anyone who fits your selection criteria can sign up. It is first-come, first-served until the number of participants you are seeking has been reached.'),
            ])->toArray(),
        ]);
    }

    public function storeRecruitment(StoreEngagementRecruitmentRequest $request, Engagement $engagement): RedirectResponse
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        return redirect(localized_route('engagements.show-criteria-selection', $engagement));
    }

    public function showCriteriaSelection(Engagement $engagement): View
    {
        return view('engagements.show-criteria-selection', [
            'title' => __('Create engagement'),
            'surtitle' => __('Create engagement'),
            'heading' => __('Confirm your participant selection criteria'),
            'project' => $engagement->project,
            'engagement' => $engagement,
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->toArray(),
            'crossDisability' => DisabilityType::where('name->en', 'Cross-disability')->first(),
            'disabilityTypes' => Options::forModels(DisabilityType::query()->where([
                ['name->en', '!=', 'Cross-disability'],
                ['name->en', '!=', 'Temporary disabilities'],
            ]))->toArray(),
            'ageBrackets' => Options::forModels(AgeBracket::class)->toArray(),
            'areaTypes' => Options::forModels(AreaType::class)->toArray(),
            'indigenousIdentities' => Options::forModels(IndigenousIdentity::class)->toArray(),
            'ethnoracialIdentities' => Options::forModels(EthnoracialIdentity::query()->where('name->en', '!=', 'White'))->toArray(),
            'women' => GenderIdentity::where('name_plural->en', 'Women')->first(),
            'nb' => GenderIdentity::where('name_plural->en', 'Non-binary people')->first(),
            'gnc' => GenderIdentity::where('name_plural->en', 'Gender non-conforming people')->first(),
            'fluid' => GenderIdentity::where('name_plural->en', 'Gender fluid people')->first(),
            'transPeople' => Constituency::where('name_plural->en', 'Trans people')->first(),
            'twoslgbtqiaplusPeople' => Constituency::where('name_plural->en', '2SLGBTQIA+ people')->first(),
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'locationTypeOptions' => Options::forArray([
                'regions' => __('Specific provinces or territories'),
                'localities' => __('Specific cities or towns'),
            ])->toArray(),
            'intersectionalOptions' => Options::forArray([
                '1' => __('No, give me a group with intersectional experiences and/or identities'),
                '0' => __('Yes, I’m looking for a group with a specific experience and/or identity (for example: Indigenous, immigrant, 2SLGBTQIA+)', ),
            ])->toArray(),
            'otherIdentityOptions' => Options::forArray([
                'age-bracket' => __('Age'),
                'gender-and-sexual-identity' => __('Gender and sexual identity'),
                'indigenous-identity' => __('Indigenous'),
                'ethnoracial-identity' => __('Race and ethnicity'),
                'refugee-or-immigrant' => __('Immigrants and/or refugees'),
                'first-language' => __('First language'),
                'area-type' => __('Living in urban, rural, or remote areas'),
            ])->nullable(__('Select a criteria…'))->toArray(),
        ]);
    }

    public function editCriteria(Engagement $engagement): View
    {
        return view('engagements.show-criteria-selection', [
            'title' => __('Manage engagement'),
            'surtitle' => __('Manage engagement'),
            'heading' => __('Edit your participant selection criteria'),
            'project' => $engagement->project,
            'engagement' => $engagement,
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->toArray(),
            'crossDisability' => DisabilityType::where('name->en', 'Cross-disability')->first(),
            'disabilityTypes' => Options::forModels(DisabilityType::query()->where([
                ['name->en', '!=', 'Cross-disability'],
                ['name->en', '!=', 'Temporary disabilities'],
            ]))->toArray(),
            'ageBrackets' => Options::forModels(AgeBracket::class)->toArray(),
            'areaTypes' => Options::forModels(AreaType::class)->toArray(),
            'indigenousIdentities' => Options::forModels(IndigenousIdentity::class)->toArray(),
            'ethnoracialIdentities' => Options::forModels(EthnoracialIdentity::query()->where('name->en', '!=', 'White'))->toArray(),
            'women' => GenderIdentity::where('name_plural->en', 'Women')->first(),
            'nb' => GenderIdentity::where('name_plural->en', 'Non-binary people')->first(),
            'gnc' => GenderIdentity::where('name_plural->en', 'Gender non-conforming people')->first(),
            'fluid' => GenderIdentity::where('name_plural->en', 'Gender fluid people')->first(),
            'transPeople' => Constituency::where('name_plural->en', 'Trans people')->first(),
            'twoslgbtqiaplusPeople' => Constituency::where('name_plural->en', '2SLGBTQIA+ people')->first(),
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'locationTypeOptions' => Options::forArray([
                'regions' => __('Specific provinces or territories'),
                'localities' => __('Specific cities or towns'),
            ])->toArray(),
            'intersectionalOptions' => Options::forArray([
                '1' => __('No, give me a group with intersectional experiences and/or identities'),
                '0' => __('Yes, I’m looking for a group with a specific experience and/or identity (for example: Indigenous, immigrant, 2SLGBTQIA+)', ),
            ])->toArray(),
            'otherIdentityOptions' => Options::forArray([
                'age-bracket' => __('Age'),
                'gender-and-sexual-identity' => __('Gender and sexual identity'),
                'indigenous-identity' => __('Indigenous'),
                'ethnoracial-identity' => __('Race and ethnicity'),
                'refugee-or-immigrant' => __('Immigrants and/or refugees'),
                'first-language' => __('First language'),
                'area-type' => __('Living in urban, rural, or remote areas'),
            ])->nullable(__('Select a criteria…'))->toArray(),
        ]);
    }

    public function updateCriteria(UpdateEngagementSelectionCriteriaRequest $request, Engagement $engagement): RedirectResponse
    {
        $matchingStrategy = $engagement->matchingStrategy;

        $engagementData = $request->safe()->only(['ideal_participants', 'minimum_participants']);

        $matchingStrategyData = $request->safe()->except(['ideal_participants', 'minimum_participants']);

        if ($matchingStrategyData['location_type'] === 'regions') {
            $matchingStrategyData['locations'] = [];
        }

        if ($matchingStrategyData['location_type'] === 'localities') {
            $matchingStrategyData['regions'] = [];
        }

        $engagement->fill($engagementData);
        $engagement->save();

        $crossDisability = DisabilityType::where('name->en', 'Cross-disability')->first();

        if ($matchingStrategyData['cross_disability'] == 1) {
            $matchingStrategy->syncRelatedCriteria('App\Models\DisabilityType', $crossDisability->id);
        } elseif ($matchingStrategyData['cross_disability'] == 0) {
            $matchingStrategy->syncRelatedCriteria('App\Models\DisabilityType', $matchingStrategyData['disability_types']);
        }

        $matchingStrategy->fill($matchingStrategyData);

        if ($matchingStrategyData['intersectional'] == 0) {
            $matchingStrategy->extra_attributes->other_identity_type = $matchingStrategyData['other_identity_type'];
            if ($matchingStrategyData['other_identity_type'] === 'age-bracket') {
                $matchingStrategy->syncMutuallyExclusiveCriteria(
                    'App\Models\AgeBracket',
                    $matchingStrategyData['age_brackets'],
                    [
                        'App\Models\Constituency',
                        'App\Models\IndigenousIdentity',
                        'App\Models\GenderIdentity',
                        'App\Models\EthnoracialIdentity',
                        'App\Models\AreaType',
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'gender-and-sexual-identity') {
                $criteria = [
                    'App\Models\GenderIdentity' => [],
                    'App\Models\Constituency' => [],
                ];

                if (in_array('women', $matchingStrategyData['gender_and_sexual_identities'])) {
                    $women = GenderIdentity::where('name_plural->en', 'Women')->first();
                    $criteria['App\Models\GenderIdentity'][] = $women->id;
                }

                if (in_array('nb-gnc-fluid-people', $matchingStrategyData['gender_and_sexual_identities'])) {
                    $nb = GenderIdentity::where('name_plural->en', 'Non-binary people')->first();
                    $criteria['App\Models\GenderIdentity'][] = $nb->id;

                    $gnc = GenderIdentity::where('name_plural->en', 'Gender non-conforming people')->first();
                    $criteria['App\Models\GenderIdentity'][] = $gnc->id;

                    $fluid = GenderIdentity::where('name_plural->en', 'Gender fluid people')->first();
                    $criteria['App\Models\GenderIdentity'][] = $fluid->id;
                }

                if (in_array('trans-people', $matchingStrategyData['gender_and_sexual_identities'])) {
                    $transPeople = Constituency::where('name_plural->en', 'Trans people')->first();
                    $criteria['App\Models\Constituency'][] = $transPeople->id;
                }

                if (in_array('2slgbtqiaplus-people', $matchingStrategyData['gender_and_sexual_identities'])) {
                    $twoslgbtqiaplusPeople = Constituency::where('name_plural->en', '2SLGBTQIA+ people')->firstOrFail();
                    $criteria['App\Models\Constituency'][] = $twoslgbtqiaplusPeople->id;
                }

                $matchingStrategy->syncUnrelatedMutuallyExclusiveCriteria(
                    $criteria,
                    [
                        'App\Models\AgeBracket',
                        'App\Models\IndigenousIdentity',
                        'App\Models\EthnoracialIdentity',
                        'App\Models\AreaType',
                    ]
                );
            }
            if ($matchingStrategyData['other_identity_type'] === 'indigenous-identity') {
                $matchingStrategy->syncMutuallyExclusiveCriteria(
                    'App\Models\IndigenousIdentity',
                    $matchingStrategyData['indigenous_identities'],
                    [
                        'App\Models\AgeBracket',
                        'App\Models\Constituency',
                        'App\Models\EthnoracialIdentity',
                        'App\Models\GenderIdentity',
                        'App\Models\AreaType',
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'ethnoracial-identity') {
                $matchingStrategy->syncMutuallyExclusiveCriteria(
                    'App\Models\EthnoracialIdentity',
                    $matchingStrategyData['ethnoracial_identities'],
                    [
                        'App\Models\AgeBracket',
                        'App\Models\Constituency',
                        'App\Models\IndigenousIdentity',
                        'App\Models\GenderIdentity',
                        'App\Models\AreaType',
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'refugee-or-immigrant') {
                $refugeeOrImmigrant = Constituency::where('name->en', 'Refugee or immigrant')->first();
                $matchingStrategy->syncMutuallyExclusiveCriteria(
                    'App\Models\Constituency',
                    $refugeeOrImmigrant->id,
                    [
                        'App\Models\AgeBracket',
                        'App\Models\IndigenousIdentity',
                        'App\Models\GenderIdentity',
                        'App\Models\EthnoracialIdentity',
                        'App\Models\AreaType',
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'first-language') {
                $languages = [];

                foreach ($matchingStrategyData['first_languages'] as $code) {
                    $languages[] = Language::firstOrCreate([
                        'code' => $code,
                        'name' => [
                            'en' => get_language_exonym($code, 'en'),
                            'fr' => get_language_exonym($code, 'fr'),
                        ],
                    ])->id;
                }

                $matchingStrategy->syncMutuallyExclusiveCriteria(
                    'App\Models\Language',
                    $languages,
                    [
                        'App\Models\AreaType',
                        'App\Models\AgeBracket',
                        'App\Models\Constituency',
                        'App\Models\IndigenousIdentity',
                        'App\Models\GenderIdentity',
                        'App\Models\EthnoracialIdentity',
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'area-type') {
                $matchingStrategy->syncMutuallyExclusiveCriteria(
                    'App\Models\AreaType',
                    $matchingStrategyData['area_types'],
                    [
                        'App\Models\AgeBracket',
                        'App\Models\Constituency',
                        'App\Models\IndigenousIdentity',
                        'App\Models\GenderIdentity',
                        'App\Models\EthnoracialIdentity',
                    ]
                );
            }
        } else {
            $matchingStrategy->extra_attributes->forget('other_identity_type');
        }

        $matchingStrategy->save();

        flash(__('Your participant selection criteria have been updated.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function show(Engagement $engagement)
    {
        return view('engagements.show', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }

    public function edit(Engagement $engagement)
    {
        return view('engagements.edit', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'timezones' => Options::forEnum(TimeZone::class)->nullable(__('Please select your time zone…'))->toArray(),
            'meetingTypes' => Options::forEnum(MeetingType::class)->toArray(),
            'weekdays' => Options::forEnum(Weekday::class)->toArray(),
            'weekdayAvailabilities' => Options::forEnum(Availability::class)->toArray(),
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->nullable(__('Choose a province or territory…'))->toArray(),
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
        ]);
    }

    public function editLanguages(Engagement $engagement): View
    {
        return view('engagements.show-language-edit', [
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'engagement' => $engagement,
        ]);
    }

    public function updateLanguages(UpdateEngagementLanguagesRequest $request, Engagement $engagement): RedirectResponse
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement translations have been updated.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function update(UpdateEngagementRequest $request, Engagement $engagement)
    {
        $data = $request->validated();

        if (isset($data['window_start_time'])) {
            $window_start_time = Carbon::createFromTimeString($data['window_start_time'])->toTimeString();
            $data['window_start_time'] = $window_start_time;
        }

        if (isset($data['window_end_time'])) {
            $window_end_time = Carbon::createFromTimeString($data['window_end_time'])->toTimeString();
            $data['window_end_time'] = $window_end_time;
        }

        if (! isset($data['accepted_formats'])) {
            $data['accepted_formats'] = [];
        }

        if (! isset($data['other_accepted_formats'])) {
            $data['other_accepted_format'] = null;
        }

        $engagement->fill($data);
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function manage(Engagement $engagement)
    {
        $connectorInvitation = $engagement->invitations->where('role', 'connector')->first() ?? null;
        $connectorInvitee = null;
        if ($connectorInvitation) {
            if ($connectorInvitation->type === 'individual') {
                $individual = User::whereBlind('email', 'email_index', $connectorInvitation->email)->first()->individual ?? null;
                $connectorInvitee = $individual && $individual->checkStatus('published') ? $individual : null;
            } elseif ($connectorInvitation->type === 'organization') {
                $connectorInvitee = Organization::where('contact_person_email', $connectorInvitation->email)->first() ?? null;
            }
        }

        return view('engagements.manage', [
            'engagement' => $engagement,
            'project' => $engagement->project,
            'connectorInvitation' => $connectorInvitation,
            'connectorInvitee' => $connectorInvitee,
        ]);
    }

    public function participate(Engagement $engagement)
    {
        return view('engagements.participate', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }
}
