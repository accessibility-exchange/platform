<?php

namespace App\Http\Controllers;

use App\Enums\AcceptedFormat;
use App\Enums\Availability;
use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\IdentityCluster;
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
use App\Mail\ContractorInvitation;
use App\Models\AccessSupport;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Language;
use App\Models\MatchingStrategy;
use App\Models\Organization;
use App\Models\Project;
use App\Notifications\OrganizationAddedToEngagement;
use App\Notifications\OrganizationRemovedFromEngagement;
use App\Notifications\ParticipantInvited;
use App\Notifications\ParticipantJoined;
use App\Notifications\ParticipantLeft;
use App\Statuses\OrganizationStatus;
use App\Traits\RetrievesUserByNormalizedEmail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\LaravelOptions\Options;

class EngagementController extends Controller
{
    use RetrievesUserByNormalizedEmail;

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
            'organization' => localized_route('engagements.show-criteria-selection', $engagement),
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

    public function criteriaSelection(Engagement $engagement): View
    {
        return view('engagements.show-criteria-selection', [
            'title' => request()->localizedRouteIs('engagements.show-criteria-selection') ? __('Create engagement') : __('Manage engagement'),
            'surtitle' => request()->localizedRouteIs('engagements.show-criteria-selection') ? __('Create engagement') : __('Manage engagement'),
            'heading' => request()->localizedRouteIs('engagements.show-criteria-selection') ? __('Confirm your participant selection criteria') : __('Edit your participant selection criteria'),
            'project' => $engagement->project,
            'engagement' => $engagement,
            'regions' => Options::forEnum(ProvinceOrTerritory::class)->toArray(),
            'disabilityTypes' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf))->toArray(),
            'ageBrackets' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Age))->toArray(),
            'areaTypes' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Area))->toArray(),
            'genderAndSexualityIdentities' => array_merge(Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Gender)->whereNot(function ($query) {
                $query->whereJsonContains('clusters', IdentityCluster::GenderDiverse);
            }))->toArray(), Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->whereNot(function ($query) {
                $query->whereJsonContains('clusters', IdentityCluster::Gender);
            }))->toArray()),
            'genderDiverseIdentities' => Identity::whereJsonContains('clusters', IdentityCluster::GenderDiverse)->get()->toArray(),
            'indigenousIdentities' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Indigenous))->toArray(),
            'ethnoracialIdentities' => Options::forModels(Identity::query()->whereJsonContains('clusters', IdentityCluster::Ethnoracial))->toArray(),
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'locationTypeOptions' => Options::forArray([
                'regions' => __('Specific provinces or territories'),
                'localities' => __('Specific cities or towns'),
            ])->toArray(),
            'intersectionalOptions' => Options::forArray([
                '1' => __('No, give me a group with intersectional experiences and/or identities'),
                '0' => __('Yes, I’m looking for a group with a specific experience and/or identity (for example: Indigenous, immigrant, 2SLGBTQIA+)'),
            ])->toArray(),
            'otherIdentityOptions' => Options::forArray([
                'age-bracket' => __('Age'),
                'gender-and-sexual-identity' => __('Gender and sexual identity'),
                'indigenous-identity' => __('Indigenous'),
                'ethnoracial-identity' => __('Race and ethnicity'),
                'refugee-or-immigrant' => __('Refugees and/or immigrants'),
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

        if ($matchingStrategyData['cross_disability_and_deaf'] == 1) {
            $matchingStrategy->detachClusters([IdentityCluster::DisabilityAndDeaf]);
        } elseif ($matchingStrategyData['cross_disability_and_deaf'] == 0) {
            $matchingStrategy->syncRelatedIdentities(IdentityCluster::DisabilityAndDeaf, $matchingStrategyData['disability_types']);
        }

        $matchingStrategy->fill($matchingStrategyData);

        $matchingStrategy->extra_attributes->intersectional = $matchingStrategyData['intersectional'];

        if ($matchingStrategyData['intersectional'] == 0) {
            $matchingStrategy->extra_attributes->other_identity_type = $matchingStrategyData['other_identity_type'];
            if ($matchingStrategyData['other_identity_type'] === 'age-bracket') {
                $matchingStrategy->languages()->detach();
                $matchingStrategy->syncMutuallyExclusiveIdentities(
                    IdentityCluster::Age,
                    $matchingStrategyData['age_brackets'],
                    [
                        IdentityCluster::Area,
                        IdentityCluster::Ethnoracial,
                        IdentityCluster::GenderAndSexuality,
                        IdentityCluster::Indigenous,
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'gender-and-sexual-identity') {
                if (isset($matchingStrategyData['nb_gnc_fluid_identity']) && $matchingStrategyData['nb_gnc_fluid_identity'] == 1) {
                    foreach (Identity::whereJsonContains('clusters', IdentityCluster::GenderDiverse)->get() as $genderDiverseIdentity) {
                        $matchingStrategyData['gender_and_sexual_identities'][] = $genderDiverseIdentity->id;
                    }
                }

                $matchingStrategy->languages()->detach();
                $matchingStrategy->syncMutuallyExclusiveIdentities(
                    IdentityCluster::GenderAndSexuality,
                    $matchingStrategyData['gender_and_sexual_identities'],
                    [
                        IdentityCluster::Age,
                        IdentityCluster::Area,
                        IdentityCluster::Ethnoracial,
                        IdentityCluster::Indigenous,
                        IdentityCluster::Status,
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'indigenous-identity') {
                $matchingStrategy->languages()->detach();
                $matchingStrategy->syncMutuallyExclusiveIdentities(
                    IdentityCluster::Indigenous,
                    $matchingStrategyData['indigenous_identities'],
                    [
                        IdentityCluster::Age,
                        IdentityCluster::Area,
                        IdentityCluster::Ethnoracial,
                        IdentityCluster::GenderAndSexuality,
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'ethnoracial-identity') {
                $matchingStrategy->languages()->detach();
                $matchingStrategy->syncMutuallyExclusiveIdentities(
                    IdentityCluster::Ethnoracial,
                    $matchingStrategyData['ethnoracial_identities'],
                    [
                        IdentityCluster::Age,
                        IdentityCluster::Area,
                        IdentityCluster::GenderAndSexuality,
                        IdentityCluster::Indigenous,
                        IdentityCluster::Status,
                    ]
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'refugee-or-immigrant') {
                $matchingStrategy->languages()->detach();
                $matchingStrategy->detachClusters([
                    IdentityCluster::Age,
                    IdentityCluster::Area,
                    IdentityCluster::Ethnoracial,
                    IdentityCluster::GenderAndSexuality,
                    IdentityCluster::Indigenous,
                ]);

                $matchingStrategy->identities()->attach(
                    Identity::whereJsonContains('clusters', IdentityCluster::Status)->pluck('id'),
                );
            }

            if ($matchingStrategyData['other_identity_type'] === 'first-language') {
                $languages = [];

                foreach ($matchingStrategyData['first_languages'] as $code) {
                    $languages[] = Language::firstOrCreate(
                        ['code' => $code],
                        [
                            'name' => [
                                'en' => get_language_exonym($code, 'en'),
                                'asl' => get_language_exonym($code, 'en'),
                                'fr' => get_language_exonym($code, 'fr'),
                                'lsq' => get_language_exonym($code, 'fr'),
                            ],
                        ],
                    )->id;
                }

                $matchingStrategy->languages()->sync($languages);
                $matchingStrategy->detachClusters([
                    IdentityCluster::Age,
                    IdentityCluster::Area,
                    IdentityCluster::Ethnoracial,
                    IdentityCluster::GenderAndSexuality,
                    IdentityCluster::Indigenous,
                    IdentityCluster::Status,
                ]);
            }

            if ($matchingStrategyData['other_identity_type'] === 'area-type') {
                $matchingStrategy->languages()->detach();
                $matchingStrategy->syncMutuallyExclusiveIdentities(
                    IdentityCluster::Area,
                    $matchingStrategyData['area_types'],
                    [
                        IdentityCluster::Age,
                        IdentityCluster::Indigenous,
                        IdentityCluster::GenderAndSexuality,
                        IdentityCluster::Ethnoracial,
                        IdentityCluster::Status,

                    ]
                );
            }
        } else {
            $matchingStrategy->extra_attributes->forget('other_identity_type');
            $matchingStrategy->languages()->detach();
            $matchingStrategy->detachClusters([
                IdentityCluster::Age,
                IdentityCluster::Area,
                IdentityCluster::Ethnoracial,
                IdentityCluster::GenderAndSexuality,
                IdentityCluster::Indigenous,
                IdentityCluster::Status,
            ]);
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
            'formats' => Options::forEnum(AcceptedFormat::class)->toArray(),
        ]);
    }

    public function editLanguages(Engagement $engagement): View
    {
        return view('engagements.show-language-edit', [
            'languages' => Options::forArray(get_available_languages(true))->nullable(__('Choose a language…'))->toArray(),
            'engagement' => $engagement,
            'project' => $engagement->project,
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

        $engagement->fill($data);
        $engagement->save();

        if ($request->input('publish')) {
            if ($engagement->fresh()->isPublishable()) {
                $engagement->update(['published_at' => now()]);
                flash(__('Your engagement has been published. [Visit engagement](:url)', ['url' => localized_route('engagements.show', $engagement)]), 'success');
            }
        } else {
            flash(__('Your engagement has been updated.'), 'success');
        }

        return redirect(localized_route('engagements.manage', $engagement));
    }

    public function manage(Engagement $engagement)
    {
        if (! $engagement->isManageable()) {
            if (is_null($engagement->format)) {
                return redirect(localized_route('engagements.show-format-selection', $engagement));
            }

            if (is_null($engagement->recruitment)) {
                return redirect(localized_route('engagements.show-recruitment-selection', $engagement));
            }
        }

        $connectorInvitation = $engagement->invitations->where('role', 'connector')->first() ?? null;
        $connectorInvitee = null;
        if ($connectorInvitation) {
            if ($connectorInvitation->type === 'individual') {
                $individual = $this->retrieveUserByEmail($connectorInvitation->email)?->individual;
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

    public function manageOrganization(Engagement $engagement): View
    {
        return view('engagements.manage-organization', [
            'engagement' => $engagement,
            'project' => $engagement->project,
            'organizations' => Options::forModels(Organization::query()->whereJsonContains('roles', 'participant')->status(new OrganizationStatus('published')))->nullable(__('Choose a community organization…'))->toArray(),
        ]);
    }

    public function addOrganization(Request $request, Engagement $engagement): RedirectResponse
    {
        $organization = Organization::find($request->input('organization_id'));

        $validator = Validator::make(
            $request->all(), [
                'organization_id' => 'required|exists:organizations,id',
            ],
            [],
            [
                'organization_id' => __('organization.singular_name'),
            ]
        );

        $validator->after(function ($validator) use ($organization) {
            if (! $organization || ! $organization->isParticipant()) {
                $validator->errors()->add(
                    'organization_id', __('The organization you have added does not participate in engagements.')
                );
            }
        });

        if ($validator->fails()) {
            return redirect(localized_route('engagements.manage-organization', $engagement))
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $engagement->organization()->associate($validated['organization_id']);

        $engagement->save();

        $organization->notify(new OrganizationAddedToEngagement($engagement));

        flash(__('You have successfully added :organization as the Community Organization you are consulting with for this engagement.', ['organization' => Organization::find($validated['organization_id'])->getTranslation('name', locale())]), 'success');

        return redirect(localized_route('engagements.manage-organization', $engagement));
    }

    public function removeOrganization(Request $request, Engagement $engagement): RedirectResponse
    {
        $organization = $engagement->organization;

        $engagement->organization()->dissociate();

        $engagement->save();

        $organization->notify(new OrganizationRemovedFromEngagement($engagement));

        flash(__('You have successfully removed :organization as the Community Organization for this engagement.', ['organization' => $organization->getTranslation('name', locale())]), 'success');

        return redirect(localized_route('engagements.manage-organization', $engagement));
    }

    public function manageParticipants(Engagement $engagement): View
    {
        return view('engagements.manage-participants', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'invitations' => $engagement->invitations->where('role', 'participant'),
            'participants' => $engagement->participants,
            'printVersion' => AccessSupport::where('name->en', 'Printed version of engagement documents')->first(),
        ]);
    }

    public function addParticipant(Engagement $engagement): View|Response
    {
        return view('engagements.add-participant', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }

    public function inviteParticipant(Request $request, Engagement $engagement): RedirectResponse
    {
        $user = null;

        if ($request->input('email')) {
            $user = $this->retrieveUserByEmail($request->input('email'));
        }

        $validator = Validator::make(
            $request->all(),
            [
                'email' => [
                    'required',
                    'email',
                    Rule::unique('invitations')->where(function ($query) use ($engagement) {
                        return $query->where([
                            ['invitationable_type', 'App\Models\Engagement'],
                            ['invitationable_id', $engagement->id],
                        ]);
                    }),
                ],
            ],
            [
                'email.required' => __('You must enter an email address.'),
                'email.unique' => __('This individual has already been invited to your engagement.'),
            ]
        );

        $validator->after(function ($validator) use ($user, $engagement) {
            if ($user) {
                $individual = $user->individual ?? null;
                if (is_null($individual) || ! $individual->isParticipant()) {
                    $validator->errors()->add('email', __('The person with the email address you provided is not a consultation participant.'));
                }

                if ($individual && $engagement->participants->contains($individual)) {
                    $validator->errors()->add('email', __('The individual with the email address you provided is already participating in this engagement.'));
                }
            }
        });

        if ($validator->fails()) {
            return redirect(localized_route('engagements.add-participant', $engagement))
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $validated['type'] = 'individual';
        $validated['role'] = 'participant';

        $invitation = $engagement->invitations()->create($validated);

        if ($user) {
            $user->notify(new ParticipantInvited($invitation));
        } else {
            Mail::to($invitation->email)->send(new ContractorInvitation($invitation));
        }

        flash(__('invitation.create_invitation_succeeded'), 'success');

        return redirect(localized_route('engagements.manage-participants', $engagement));
    }

    public function signUp(Engagement $engagement): View
    {
        return view('engagements.sign-up', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'individual' => Auth::user()->individual,
        ]);
    }

    public function join(Request $request, Engagement $engagement): RedirectResponse
    {
        $engagement->participants()->save(Auth::user()->individual, ['status' => 'confirmed']);

        $engagement->project->notify(new ParticipantJoined($engagement));

        flash(__('You have successfully signed up for this engagement.'), 'success');

        return redirect(localized_route('engagements.confirm-access-needs', $engagement));
    }

    public function confirmAccessNeeds(Engagement $engagement): RedirectResponse|View
    {
        if (url()->previous() !== localized_route('engagements.sign-up', $engagement)) {
            return redirect(localized_route('engagements.show', $engagement));
        }

        return view('engagements.confirm-access-needs', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'individual' => Auth::user()->individual,
        ]);
    }

    public function storeAccessNeedsPermissions(Request $request, Engagement $engagement): RedirectResponse
    {
        $request->validate([
            'share_access_needs' => 'required|boolean',
        ]);

        $engagement->participants()->syncWithoutDetaching([Auth::user()->individual->id => ['status' => 'confirmed', 'share_access_needs' => $request->input('share_access_needs')]]);

        flash(__('Your preference for sharing your access needs has been saved.'), 'success');

        return redirect(localized_route('engagements.show', $engagement));
    }

    public function confirmLeave(Engagement $engagement): View
    {
        return view('engagements.leave', [
            'project' => $engagement->project,
            'engagement' => $engagement,
        ]);
    }

    public function leave(Request $request, Engagement $engagement): RedirectResponse
    {
        Auth::user()->individual->engagements()->detach($engagement->id);

        $engagement->project->notify(new ParticipantLeft($engagement));

        flash(__('You have successfully left this engagement.'), 'success');

        return redirect(localized_route('engagements.show', $engagement));
    }

    public function manageAccessNeeds(Engagement $engagement): View
    {
        $printVersion = AccessSupport::where('name->en', 'Printed version of engagement documents')->first();
        $additionalConcerns = AccessSupport::where('name->en', 'I would like to speak to someone to discuss additional access needs or concerns')->first();

        return view('engagements.manage-access-needs', [
            'project' => $engagement->project,
            'engagement' => $engagement,
            'participants' => $engagement->participants,
            'anonymizableAccessNeeds' => $engagement->accessNeeds()->where('anonymizable', true)->get()->unique()->sortBy('name'),
            'accessNeeds' => $engagement->accessNeeds()->where('anonymizable', false)->get()->unique()->filter(fn ($item) => $item->id !== $printVersion->id)->sortBy('name'),
            'invitations' => collect([]),
            'printVersion' => $printVersion,
            'additionalConcerns' => $additionalConcerns,
        ]);
    }
}
