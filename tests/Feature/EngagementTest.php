<?php

use App\Enums\EngagementFormat;
use App\Enums\IdentityCluster;
use App\Enums\IdentityType;
use App\Enums\LocationType;
use App\Enums\MeetingType;
use App\Enums\UserContext;
use App\Http\Requests\StoreEngagementRequest;
use App\Http\Requests\UpdateEngagementRequest;
use App\Http\Requests\UpdateEngagementSelectionCriteriaRequest;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\PaymentType;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Statuses\EngagementStatus;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\IdentitySeeder;
use Illuminate\Support\Carbon;

test('users with regulated organization admin role can create engagements', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('engagements.show-language-selection', $project));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('engagements.store-languages', $project), [
        'languages' => config('locales.supported'),
    ]);
    $response->assertRedirect(localized_route('engagements.create', $project));
    $response->assertSessionHas('languages', config('locales.supported'));

    $response = $this->actingAs($user)->get(localized_route('engagements.create', $project));
    $response->assertOk();

    $data = StoreEngagementRequest::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this->withSession([
        'languages' => config('locales.supported'),
    ])->actingAs($user)->post(localized_route('engagements.store', $project), $data);

    $response->assertSessionHasNoErrors();

    $engagement = Engagement::where('name->en', $data['name']['en'])->first();

    $response->assertRedirect(localized_route('engagements.show-format-selection', $engagement));

    // Test creation incomplete, attempted to skip to manage page
    $engagement->refresh();
    expect($engagement->isManageable())->toBeFalse();
    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertRedirect(localized_route('engagements.show-format-selection', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.show-format-selection', $engagement));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('engagements.store-format', $engagement), [
        'format' => 'survey',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.show-recruitment-selection', $engagement));

    // Test creation incomplete, attempted to skip to manage page
    $engagement->refresh();
    expect($engagement->isManageable())->toBeFalse();
    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertRedirect(localized_route('engagements.show-recruitment-selection', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.show-recruitment-selection', $engagement));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('engagements.store-recruitment', $engagement), [
        'recruitment' => 'open-call',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.show-criteria-selection', $engagement));

    expect($engagement->matchingStrategy->location_summary)->toEqual(['All provinces and territories']);
    expect($engagement->matchingStrategy->disability_and_deaf_group_summary)->toEqual(['Cross disability (includes people with disabilities, Deaf people, and supporters)']);
    expect($engagement->matchingStrategy->other_identities_summary)->toEqual(['Intersectional - This engagement is looking for people who have all sorts of different identities and lived experiences, such as race, gender, age, sexual orientation, and more.']);

    // Test creation skipped selection criteria
    $engagement->refresh();
    expect($engagement->isManageable())->toBeTrue();
    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('engagements.show-criteria-selection', $engagement));
    $response->assertOk();

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create();

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $data = StoreEngagementRequest::factory()->create([
        'project_id' => $project->id,
        'who' => 'organization',
    ]);

    $response = $this->withSession([
        'languages' => config('locales.supported'),
    ])->actingAs($user)->post(localized_route('engagements.store', $project), $data);

    $communityOrganizationEngagement = Engagement::where('name->en', $data['name']['en'])->first();

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.show-criteria-selection', $communityOrganizationEngagement));
});

test('users without regulated organization admin role cannot create engagements', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('engagements.create', $project));
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->get(localized_route('engagements.create', $project));
    $response->assertForbidden();
});

test('users can view engagements', function () {
    $this->seed(IdentitySeeder::class);

    $user = User::factory()->create();
    $engagement = Engagement::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('engagements.show', $engagement));
    $response->assertOk();
});

test('guests cannot view engagements', function () {
    $engagement = Engagement::factory()->create();

    $response = $this->get(localized_route('engagements.show', $engagement));
    $response->assertRedirect(localized_route('login'));
});

test('users with regulated organization admin role can edit engagements', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('engagements.edit-criteria', $engagement));
    $response->assertOk();

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'location_type' => LocationType::Localities->value,
        'regions' => $engagement->matchingStrategy->regions ?? [],
        'locations' => [
            [
                'region' => 'NS',
                'locality' => 'Halifax',
            ],
        ],
        'cross_disability_and_deaf' => 0,
        'disability_types' => [
            Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first()->id,
        ],
        'intersectional' => 0,
        'other_identity_type' => IdentityType::AgeBracket->value,
        'age_brackets' => [Identity::whereJsonContains('clusters', IdentityCluster::Age)->first()->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('Halifax, Nova Scotia');
    $response->assertSee(Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first()->name);
    $response->assertSee(Identity::whereJsonContains('clusters', IdentityCluster::Age)->first()->name);

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
        'nb_gnc_fluid_identity' => 1,
        'gender_and_sexual_identities' => [Identity::whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->first()->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));
    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));

    $response->assertSee(Identity::whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->first()->name);
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::GenderDiverse)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::IndigenousIdentity->value,
        'indigenous_identities' => Identity::whereJsonContains('clusters', IdentityCluster::Indigenous)->get()->modelKeys(),
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::Indigenous)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::EthnoracialIdentity->value,
        'ethnoracial_identities' => Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->pluck('id')->toArray(),
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::RefugeeOrImmigrant->value,
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::Status)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::FirstLanguage->value,
        'first_languages' => ['fr', 'lsq'],
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('French');
    $response->assertSee('Quebec Sign Language');

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::AreaType->value,
        'area_types' => Identity::whereJsonContains('clusters', IdentityCluster::Area)->pluck('id')->toArray(),
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::Area)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $response = $this->actingAs($user)->get(localized_route('engagements.edit', $engagement));
    $response->assertOk();

    $data = UpdateEngagementRequest::factory()->create();

    $response = $this->actingAs($user)->put(localized_route('engagements.update', $engagement), $data);

    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    expect($engagement->fresh()->description)->toEqual($data['description']['en']);

    $engagement->update(['format' => 'interviews']);

    $engagement = $engagement->fresh();

    $response = $this->actingAs($user)->put(localized_route('engagements.update', $engagement), array_merge($data, [
        'window_start_date' => '2022-11-01',
        'window_end_date' => '2022-11-15',
        'window_start_time' => '9:00',
        'window_end_time' => '17:00',
        'timezone' => 'America/Toronto',
        'weekday_availabilities' => [
            'monday' => 'yes',
            'tuesday' => 'yes',
            'wednesday' => 'yes',
            'thursday' => 'yes',
            'friday' => 'yes',
            'saturday' => 'no',
            'sunday' => 'no',
        ],
        'meeting_types' => ['in_person', 'web_conference', 'phone'],
        'street_address' => '1223 Main Street',
        'locality' => 'Anytown',
        'region' => 'ON',
        'postal_code' => 'M4W 1E6',
        'meeting_software' => 'WebMeetingApp',
        'meeting_url' => 'https://example.com/meet',
        'meeting_phone' => '6476231847',
        'materials_by_date' => '2022-11-01',
        'complete_by_date' => '2022-11-15',
        'accepted_formats' => ['writing', 'audio', 'video'],
        'signup_by_date' => '2022-10-31',
    ]));

    $response->assertSessionHasNoErrors();

    $engagement = $engagement->fresh();

    expect($engagement->window_start_time->format('H:i:s'))->toEqual('09:00:00');
    expect($engagement->window_end_time->format('H:i:s'))->toEqual('17:00:00');
    expect($engagement->meetingTypesIncludes('in_person'))->toBeTrue();
    expect($engagement->display_meeting_types)->toContain('In person');
    expect($engagement->display_meeting_types)->toContain('Virtual – web conference');
    expect($engagement->display_meeting_types)->toContain('Virtual – phone call');

    $response = $this->actingAs($user)->get(localized_route('engagements.edit-languages', $engagement));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('engagements.update-languages', $engagement), [
        'languages' => ['en', 'fr'],
    ]);

    $engagement = $engagement->fresh();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));
    expect($engagement->languages)->toEqual(['en', 'fr']);
});

test('users without regulated organization admin role cannot edit engagements', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('engagements.edit', $engagement));
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('engagements.update', $engagement), [
        'name' => ['en' => 'My renamed engagement'],
    ]);
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->get(localized_route('engagements.edit', $engagement));
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->put(localized_route('engagements.update', $engagement), [
        'name' => ['en' => 'My renamed engagement'],
    ]);
    $response->assertForbidden();
});

test('update engagement request validation errors', function ($state, $errors, $modifiers = []) {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $format = $modifiers['format'] ?? EngagementFormat::Workshop->value;
    $engagement = Engagement::factory()->create([
        'project_id' => $project->id,
        'format' => $format,
    ]);

    $requestFactory = UpdateEngagementRequest::factory();

    $formatTransformer = match ($format) {
        EngagementFormat::Interviews->value => 'formatInterview',
        EngagementFormat::Survey->value, EngagementFormat::OtherAsync->value => 'formatAsync',
        default => null
    };

    if ($formatTransformer) {
        $requestFactory = $requestFactory->$formatTransformer();
    }

    $meetingTypeTransformer = match ($modifiers['meetingType'] ?? '') {
        MeetingType::InPerson->value => 'meetingInPerson',
        MeetingType::Phone->value, EngagementFormat::OtherAsync->value => 'meetingPhone',
        MeetingType::WebConference->value, EngagementFormat::OtherAsync->value => 'meetingWebConference',
        default => null
    };

    if ($meetingTypeTransformer) {
        $requestFactory = $requestFactory->$meetingTypeTransformer();
    }

    $data = $requestFactory->without($modifiers['without'] ?? [])->create($state);

    $response = $this->actingAs($user)->put(localized_route('engagements.update', $engagement), $data);
    $response->assertSessionHasErrors($errors);
})->with('updateEngagementRequestValidationErrors');

test('update engagement selection criteria request validation errors', function ($state, $errors, $without = []) {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()->create([
        'project_id' => $project->id,
    ]);

    $requestFactory = UpdateEngagementSelectionCriteriaRequest::factory();

    $data = $requestFactory->without($without ?? [])->create($state);
    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);
    $response->assertSessionHasErrors($errors);
})->with('updateEngagementSelectionCriteriaRequestValidationErrors');

test('users with regulated organization admin role can manage engagements', function () {
    $this->seed(IdentitySeeder::class);

    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertOk();
});

test('users without regulated organization admin role cannot manage engagements', function () {
    $user = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
    ]);
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->get(localized_route('engagements.manage', $engagement));
    $response->assertForbidden();
});

test('engagements can reflect parent project’s estimate and agreement status', function () {
    $engagement = Engagement::factory()->create();

    expect($engagement->hasEstimateAndAgreement())->toBeFalse();

    $project = $engagement->project;

    $project->update(['estimate_requested_at' => now()]);

    expect($engagement->hasEstimateAndAgreement())->toBeFalse();

    $project->update(['estimate_approved_at' => now()]);

    expect($engagement->hasEstimateAndAgreement())->toBeFalse();

    $project->update(['agreement_received_at' => now()]);

    expect($engagement->hasEstimateAndAgreement())->toBeTrue();
});

test('engagement isPublishable()', function ($expected, $data, $meetings = false, $estimatesAndAgreements = true, $projectableData = []) {
    $project = Project::factory()->create();
    $regulatedOrganization = $project->projectable;
    $regulatedOrganization->update($projectableData);
    $regulatedOrganization = $regulatedOrganization->fresh();
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    // Fill data so that we don't hit a Database Integrity constraint violation during creation
    $engagement = Engagement::factory()->create(['project_id' => $project->id, 'published_at' => null]);
    $engagement->fill($data);
    $engagement->save();
    $engagement = $engagement->fresh();

    if ($meetings) {
        $engagement->meetings()->save(Meeting::factory()->create());
    }

    if ($estimatesAndAgreements) {
        $project->update([
            'estimate_requested_at' => now(),
            'estimate_returned_at' => now(),
            'estimate_approved_at' => now(),
            'agreement_received_at' => now(),
        ]);
    }

    expect($engagement->isPublishable())->toBe($expected);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.edit', $engagement));
    if ($expected) {
        $response->assertDontSee('aria-disabled="true"', false);
    } else {
        $response->assertSee('aria-disabled="true"', false);
    }

    $response = $this->actingAs($regulatedOrganizationUser)->put(localized_route('engagements.update', $engagement), array_merge($data, ['publish' => 1]));

    $engagement = $engagement->fresh();
    expect($engagement->checkStatus(new EngagementStatus('published')))->toEqual($expected);
})->with('engagementIsPublishable');

test('admins can see engagement if it isPreviewable()', function () {
    $this->seed(ImpactSeeder::class);
    $project = Project::factory()->create([
        'contact_person_phone' => '4165555555',
        'contact_person_response_time' => ['en' => '48 hours'],
        'preferred_contact_method' => 'required',
        'team_trainings' => [
            [
                'date' => date('Y-m-d', time()),
                'name' => 'test training',
                'trainer_name' => 'trainer',
                'trainer_url' => 'http://example.com',
            ],
        ],
        'published_at' => now(),
    ]);
    $project->impacts()->attach(Impact::first()->id);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );
    $individualUser = Individual::factory()->create()->user;
    $adminUser = User::factory()->create(['context' => UserContext::Administrator->value]);

    // Fill data so that we don't hit a Database Integrity constraint violation during creation
    $engagement = Engagement::factory()->create(['project_id' => $project->id, 'published_at' => null]);
    $engagement->fill([
        'name' => ['en' => 'Workshop'],
        'languages' => ['en', 'fr', 'asl', 'sql'],
        'who' => 'organization',
        'paid' => true,
        'description' => ['en' => 'This is what we are doing'],
        'signup_by_date' => '2022-10-02',
    ]);

    $engagement->save();
    $engagement = $engagement->fresh();

    $engagement->meetings()->save(Meeting::factory()->create());

    expect($engagement->isPreviewable())->toBeTrue();

    //Access draft engagement as Regulated Organization admin
    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('projects.show', $project));
    $response->assertOk();
    expect($response['engagements']->contains($engagement))->toBeTrue();

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.show', $engagement));
    $response->assertOk();

    //Access draft engagement as site admin
    $response = $this->actingAs($adminUser)->get(localized_route('projects.show', $project));
    $response->assertOk();
    expect($response['engagements']->contains($engagement))->toBeTrue();

    $response = $this->actingAs($adminUser)->get(localized_route('engagements.show', $engagement));
    $response->assertOk();

    //Access draft engagement as an Individual user
    $response = $this->actingAs($individualUser)->get(localized_route('projects.show', $project));
    $response->assertOk();
    expect($response['engagements']->contains($engagement))->toBeFalse();

    $response = $this->actingAs($individualUser)->get(localized_route('engagements.show', $engagement));
    $response->assertNotFound();
});

test('engagement participants can be listed by administrator or community connector', function () {
    $user = User::factory()->create();

    $connectorUser = User::factory()->create();
    $connectorUser->individual->update(['roles' => ['connector']]);
    $connectorUser->individual->publish();
    $individualConnector = $connectorUser->individual->fresh();

    $connectorOrganization = Organization::factory()->create(['roles' => ['connector'], 'published_at' => now()]);
    $connectorOrganizationUser = User::factory()->create(['context' => UserContext::Organization->value]);
    $connectorOrganization->users()->attach(
        $connectorOrganizationUser,
        ['role' => 'admin']
    );

    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $response = $this->actingAs($user)->get(localized_route('engagements.manage-participants', $engagement));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertForbidden();

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-participants', $engagement));
    $response->assertOk();
    $response->assertDontSee('Add participant');

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertOk();

    $engagement->update(['individual_connector_id' => $individualConnector->id]);
    $engagement = $engagement->fresh();

    $response = $this->actingAs($connectorUser)->get(localized_route('engagements.manage-participants', $engagement));
    $response->assertOk();
    $response->assertSee('Add participant');

    $response = $this->actingAs($connectorUser)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertOk();

    $engagement->update(['individual_connector_id' => null, 'organizational_connector_id' => $connectorOrganization->id]);
    $engagement = $engagement->fresh();

    $response = $this->actingAs($connectorOrganizationUser)->get(localized_route('engagements.manage-participants', $engagement));
    $response->assertOk();
    $response->assertSee('Add participant');

    $response = $this->actingAs($connectorOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertOk();
});

test('other access needs show in manage participants', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'open-call']);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    // user no other access needs
    $noOtherAccessNeedsUser = User::factory()->create();
    $noOtherAccessNeedsUser->individual->update(['roles' => ['participant'], 'region' => 'NS', 'locality' => 'Bridgewater']);
    $noOtherAccessNeedsUser->individual->paymentTypes()->attach(PaymentType::first());
    $engagement->participants()->save($noOtherAccessNeedsUser->individual, ['status' => 'confirmed', 'share_access_needs' => '0']);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertOk();
    $response->assertViewHas('otherAccessNeeds');
    expect($response['otherAccessNeeds'])->toBeEmpty();

    // user with other access needs
    $otherAccessNeed = 'custom access need';
    $otherAccessNeedsUser = User::factory()->create();
    $otherAccessNeedsUser->individual->update([
        'roles' => ['participant'],
        'region' => 'NS',
        'locality' => 'Bridgewater',
        'other_access_need' => $otherAccessNeed,
    ]);
    $otherAccessNeedsUser->individual->paymentTypes()->attach(PaymentType::first());
    $engagement->participants()->save($otherAccessNeedsUser->individual, ['status' => 'confirmed', 'share_access_needs' => '0']);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertOk();
    $response->assertViewHas('otherAccessNeeds');
    expect($response['otherAccessNeeds'])->toEqualCanonicalizing(collect([$otherAccessNeed]));

    // second user with same other access needs. $otherAccessNeeds shouldn't have duplicates
    $secondOtherAccessNeedsUser = User::factory()->create();
    $secondOtherAccessNeedsUser->individual->update([
        'roles' => ['participant'],
        'region' => 'NS',
        'locality' => 'Bridgewater',
        'other_access_need' => $otherAccessNeed,
    ]);
    $secondOtherAccessNeedsUser->individual->paymentTypes()->attach(PaymentType::first());
    $engagement->participants()->save($secondOtherAccessNeedsUser->individual, ['status' => 'confirmed', 'share_access_needs' => '0']);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertOk();
    $response->assertViewHas('otherAccessNeeds');
    expect($response['otherAccessNeeds'])->toEqualCanonicalizing(collect([$otherAccessNeed]));

    // third user with different other access needs.
    $differentOtherAccessNeed = 'different custom access need';
    $thirdOtherAccessNeedsUser = User::factory()->create();
    $thirdOtherAccessNeedsUser->individual->update([
        'roles' => ['participant'],
        'region' => 'NS',
        'locality' => 'Bridgewater',
        'other_access_need' => $differentOtherAccessNeed,
    ]);
    $thirdOtherAccessNeedsUser->individual->paymentTypes()->attach(PaymentType::first());
    $engagement->participants()->save($thirdOtherAccessNeedsUser->individual, ['status' => 'confirmed', 'share_access_needs' => '0']);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertOk();
    $response->assertViewHas('otherAccessNeeds');
    expect($response['otherAccessNeeds'])->toEqualCanonicalizing(collect([$otherAccessNeed, $differentOtherAccessNeed]));
});

test('project can show upcoming engagements', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $upcomingEngagement = Engagement::factory()->create([
        'project_id' => $project->id,
        'signup_by_date' => Carbon::now()->addWeeks(2),
    ]);
    $startedEngagement = Engagement::factory()->create([
        'project_id' => $project->id,
        'signup_by_date' => Carbon::now()->subWeeks(2),
    ]);

    expect($project->engagements->pluck('id')->toArray())
        ->toContain($upcomingEngagement->id)
        ->toContain($startedEngagement->id);

    expect($project->upcomingEngagements)->toHaveCount(1);
    expect($project->upcomingEngagements->first()->is($upcomingEngagement))->toBeTrue();
});
