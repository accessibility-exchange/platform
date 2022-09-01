<?php

namespace App\Http\Controllers;

use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\ProvinceOrTerritory;
use App\Http\Requests\StoreEngagementLanguagesRequest;
use App\Http\Requests\StoreEngagementOutreachRequest;
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
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
            'formats' => Options::forEnum(EngagementFormat::class)->toArray(),
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

        return redirect(localized_route('engagements.show-outreach-selection', $engagement));
    }

    public function showOutreachSelection(Engagement $engagement): View
    {
        return view('engagements.show-outreach-selection', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }

    public function storeOutreach(StoreEngagementOutreachRequest $request, Engagement $engagement): RedirectResponse
    {
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        $engagement = $engagement->fresh();

        $redirect = match ($engagement->who) {
            'organization' => localized_route('engagements.manage', $engagement),
            default => localized_route('engagements.show-recruitment-selection', $engagement),
        };

        return redirect($redirect);
    }

    public function showRecruitmentSelection(Engagement $engagement): View
    {
        return view('engagements.show-recruitment-selection', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'recruitments' => Options::forEnum(EngagementRecruitment::class)->toArray(),
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
        $engagement->fill($request->validated());
        $engagement->save();

        flash(__('Your engagement has been updated.'), 'success');

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function manage(Engagement $engagement)
    {
        return view('engagements.manage', [
            'engagement' => $engagement,
            'project' => $engagement->project,
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
