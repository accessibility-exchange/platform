<?php

use App\Enums\Compensation;
use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\EngagementSignUpStatus;
use App\Enums\IdentityCluster;
use App\Enums\IdentityType;
use App\Enums\LocationType;
use App\Enums\MeetingType;
use App\Enums\ProjectInitiator;
use App\Enums\SeekingForEngagement;
use App\Enums\UserContext;
use App\Http\Requests\StoreEngagementRequest;
use App\Http\Requests\UpdateEngagementRequest;
use App\Http\Requests\UpdateEngagementSelectionCriteriaRequest;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\Invitation;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\PaymentType;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Sector;
use App\Models\User;
use App\Statuses\EngagementStatus;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ImpactSeeder;
use Illuminate\Support\Carbon;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Laravel\withSession;

test('users with regulated organization admin role can create engagements', function () {
    seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->get(localized_route('engagements.show-language-selection', $project))
        ->assertOk();

    actingAs($user)->post(localized_route('engagements.store-languages', $project), [
        'languages' => config('locales.supported'),
    ])
        ->assertRedirect(localized_route('engagements.create', $project))
        ->assertSessionHas('languages', config('locales.supported'));

    actingAs($user)->get(localized_route('engagements.create', $project))
        ->assertOk();

    $data = StoreEngagementRequest::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = withSession([
        'languages' => config('locales.supported'),
    ])->actingAs($user)->post(localized_route('engagements.store', $project), $data);

    $response->assertSessionHasNoErrors();

    $engagement = Engagement::where('name->en', $data['name']['en'])->first();

    $response->assertRedirect(localized_route('engagements.show-format-selection', $engagement));

    // Test creation incomplete, attempted to skip to manage page
    $engagement->refresh();
    expect($engagement->isManageable())->toBeFalse();
    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertRedirect(localized_route('engagements.show-format-selection', $engagement));

    actingAs($user)->get(localized_route('engagements.show-format-selection', $engagement))
        ->assertOk();

    actingAs($user)->put(localized_route('engagements.store-format', $engagement), [
        'format' => 'survey',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.show-recruitment-selection', $engagement));

    // Test creation incomplete, attempted to skip to manage page
    $engagement->refresh();
    expect($engagement->isManageable())->toBeFalse();
    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertRedirect(localized_route('engagements.show-recruitment-selection', $engagement));

    actingAs($user)->get(localized_route('engagements.show-recruitment-selection', $engagement))
        ->assertOk();

    actingAs($user)->put(localized_route('engagements.store-recruitment', $engagement), [
        'recruitment' => 'open-call',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.show-criteria-selection', $engagement));

    expect($engagement->matchingStrategy->location_summary)->toEqual(['All provinces and territories']);
    expect($engagement->matchingStrategy->disability_and_deaf_group_summary)->toEqual(['Cross disability (includes people with disabilities, Deaf people, and supporters)']);
    expect($engagement->matchingStrategy->other_identities_summary)->toEqual(['Intersectional - This engagement is looking for people who have all sorts of different identities and lived experiences, such as race, gender, age, sexual orientation, and more.']);

    // Test creation skipped selection criteria
    $engagement->refresh();
    expect($engagement->isManageable())->toBeTrue();
    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertOk();

    actingAs($user)->get(localized_route('engagements.show-criteria-selection', $engagement))
        ->assertOk();

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create();

    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    $data = StoreEngagementRequest::factory()->create([
        'project_id' => $project->id,
        'who' => 'organization',
    ]);

    $response = withSession([
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

    actingAs($user)->get(localized_route('engagements.create', $project))
        ->assertForbidden();

    actingAs($other_user)->get(localized_route('engagements.create', $project))
        ->assertForbidden();
});

test('store engagement languages request validation errors', function (array $state, array $errors) {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->post(localized_route('engagements.store-languages', $project), $state)
        ->assertSessionHasErrors($errors);
})->with('storeEngagementLanguagesRequestValidationErrors');

test('store engagement request validation errors', function (array $state, array $errors) {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    StoreEngagementRequest::factory()->state([
        'project_id' => $project->id,
    ])->fake();

    actingAs($user)->post(localized_route('engagements.store', $project), $state)
        ->assertSessionHasErrors($errors);
})->with('storeEngagementRequestValidationErrors');

test('store engagement format request validation errors', function (array $state, array $errors) {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()
        ->for($project)
        ->create([]);

    actingAs($user)->put(localized_route('engagements.store-format', $engagement), $state)
        ->assertSessionHasErrors($errors);
})->with('storeEngagementFormatRequestValidationErrors');

test('store engagement recruitment request validation errors', function (array $state, array $errors) {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()
        ->for($project)
        ->create([]);

    actingAs($user)->put(localized_route('engagements.store-recruitment', $engagement), $state)
        ->assertSessionHasErrors($errors);
})->with('storeEngagementRecruitmentRequestValidationErrors');

test('users can view engagements', function () {
    $user = User::factory()->create();
    $engagement = Engagement::factory()->create();

    actingAs($user)->get(localized_route('engagements.show', $engagement))
        ->assertOk();
});

test('guests cannot view engagements', function () {
    $engagement = Engagement::factory()->create();

    get(localized_route('engagements.show', $engagement))
        ->assertRedirect(localized_route('login'));

    get(localized_route('engagements.index'))
        ->assertRedirect(localized_route('login'));
});

test('users with regulated organization admin role can edit engagements', function () {
    seed(DatabaseSeeder::class);

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

    actingAs($user)->get(localized_route('engagements.edit-criteria', $engagement))
        ->assertOk();

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

    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertSee('Halifax, Nova Scotia')
        ->assertSee(Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first()->name)
        ->assertSee(Identity::whereJsonContains('clusters', IdentityCluster::Age)->first()->name);

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::GenderAndSexualIdentity->value,
        'nb_gnc_fluid_identity' => 1,
        'gender_and_sexual_identities' => [Identity::whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->first()->id],
    ]);

    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = actingAs($user)->get(localized_route('engagements.manage', $engagement));

    $response->assertSee(Identity::whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->first()->name);
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::GenderDiverse)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::IndigenousIdentity->value,
        'indigenous_identities' => Identity::whereJsonContains('clusters', IdentityCluster::Indigenous)->get()->modelKeys(),
    ]);

    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = actingAs($user)->get(localized_route('engagements.manage', $engagement));
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::Indigenous)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::EthnoracialIdentity->value,
        'ethnoracial_identities' => Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->pluck('id')->toArray(),
    ]);

    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = actingAs($user)->get(localized_route('engagements.manage', $engagement));
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::RefugeeOrImmigrant->value,
    ]);

    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = actingAs($user)->get(localized_route('engagements.manage', $engagement));
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::Status)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::FirstLanguage->value,
        'first_languages' => ['fr', 'lsq'],
    ]);

    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertSee('French')
        ->assertSee('Quebec Sign Language');

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => IdentityType::AreaType->value,
        'area_types' => Identity::whereJsonContains('clusters', IdentityCluster::Area)->pluck('id')->toArray(),
    ]);

    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = actingAs($user)->get(localized_route('engagements.manage', $engagement));
    foreach (Identity::whereJsonContains('clusters', IdentityCluster::Area)->pluck('name') as $identity) {
        $response->assertSee($identity);
    }

    actingAs($user)->get(localized_route('engagements.edit', $engagement))
        ->assertOk();

    $data = UpdateEngagementRequest::factory()->create();

    actingAs($user)->put(localized_route('engagements.update', $engagement), $data)
        ->assertRedirect(localized_route('engagements.manage', $engagement));

    expect($engagement->fresh()->description)->toEqual($data['description']['en']);

    $engagement->update(['format' => 'interviews']);

    $engagement = $engagement->fresh();

    actingAs($user)->put(localized_route('engagements.update', $engagement), array_merge($data, [
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
    ]))->assertSessionHasNoErrors();

    $engagement = $engagement->fresh();

    expect($engagement->window_start_time->format('H:i:s'))->toEqual('09:00:00');
    expect($engagement->window_end_time->format('H:i:s'))->toEqual('17:00:00');
    expect($engagement->meetingTypesIncludes('in_person'))->toBeTrue();
    expect($engagement->display_meeting_types)->toContain('In person');
    expect($engagement->display_meeting_types)->toContain('Virtual – web conference');
    expect($engagement->display_meeting_types)->toContain('Virtual – phone call');

    actingAs($user)->get(localized_route('engagements.edit-languages', $engagement))
        ->assertOk();

    $response = actingAs($user)->put(localized_route('engagements.update-languages', $engagement), [
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

    actingAs($user)->get(localized_route('engagements.edit', $engagement))
        ->assertForbidden();

    actingAs($user)->put(localized_route('engagements.update', $engagement), [
        'name' => ['en' => 'My renamed engagement'],
    ])->assertForbidden();

    actingAs($other_user)->get(localized_route('engagements.edit', $engagement))
        ->assertForbidden();

    actingAs($other_user)->put(localized_route('engagements.update', $engagement), [
        'name' => ['en' => 'My renamed engagement'],
    ])->assertForbidden();
});

test('update engagement request validation errors', function (array $state, array $errors, array $modifiers = []) {
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
        MeetingType::Phone->value => 'meetingPhone',
        MeetingType::WebConference->value => 'meetingWebConference',
        default => null
    };

    if ($meetingTypeTransformer) {
        $requestFactory = $requestFactory->$meetingTypeTransformer();
    }

    $data = $requestFactory->without($modifiers['without'] ?? [])->create($state);

    actingAs($user)->put(localized_route('engagements.update', $engagement), $data)
        ->assertSessionHasErrors($errors);
})->with('updateEngagementRequestValidationErrors');

test('update engagement languages request validation errors', function (array $state, array $errors) {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);
    $engagement = Engagement::factory()
        ->for($project)
        ->create([]);

    actingAs($user)->put(localized_route('engagements.update-languages', $engagement), $state)
        ->assertSessionHasErrors($errors);
})->with('updateEngagementLanguagesRequestValidationErrors');

test('update engagement selection criteria request validation errors', function (array $state, array $errors, array $without = []) {
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
    actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data)
        ->assertSessionHasErrors($errors);
})->with('updateEngagementSelectionCriteriaRequestValidationErrors');

test('users with regulated organization admin role can manage engagements', function () {
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

    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertOk();
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

    actingAs($user)->get(localized_route('engagements.manage', $engagement))
        ->assertForbidden();

    actingAs($other_user)->get(localized_route('engagements.manage', $engagement))
        ->assertForbidden();
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

    $response = actingAs($regulatedOrganizationUser)->get(localized_route('engagements.edit', $engagement));
    if ($expected) {
        $response->assertDontSee('aria-disabled="true"', false);
    } else {
        $response->assertSee('aria-disabled="true"', false);
    }

    actingAs($regulatedOrganizationUser)->put(localized_route('engagements.update', $engagement), array_merge($data, ['publish' => 1]));

    $engagement = $engagement->fresh();
    expect($engagement->checkStatus(new EngagementStatus('published')))->toEqual($expected);
})->with('engagementIsPublishable');

test('admins can see engagement if it isPreviewable()', function () {
    seed(ImpactSeeder::class);
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
    $response = actingAs($regulatedOrganizationUser)->get(localized_route('projects.show', $project));
    $response->assertOk();
    expect($response['engagements']->contains($engagement))->toBeTrue();

    actingAs($regulatedOrganizationUser)->get(localized_route('engagements.show', $engagement))
        ->assertOk();

    //Access draft engagement as site admin
    $response = actingAs($adminUser)->get(localized_route('projects.show', $project));
    $response->assertOk();
    expect($response['engagements']->contains($engagement))->toBeTrue();

    actingAs($adminUser)->get(localized_route('engagements.show', $engagement))
        ->assertOk();

    //Access draft engagement as an Individual user
    $response = actingAs($individualUser)->get(localized_route('projects.show', $project));
    $response->assertOk();
    expect($response['engagements']->contains($engagement))->toBeFalse();

    actingAs($individualUser)->get(localized_route('engagements.show', $engagement))
        ->assertNotFound();
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

    actingAs($user)->get(localized_route('engagements.manage-participants', $engagement))
        ->assertForbidden();

    actingAs($user)->get(localized_route('engagements.manage-access-needs', $engagement))
        ->assertForbidden();

    actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-participants', $engagement))
        ->assertOk()
        ->assertDontSee('Add participant');

    actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement))
        ->assertOk();

    $engagement->update(['individual_connector_id' => $individualConnector->id]);
    $engagement = $engagement->fresh();

    actingAs($connectorUser)->get(localized_route('engagements.manage-participants', $engagement))
        ->assertOk()
        ->assertSee('Add participant');

    actingAs($connectorUser)->get(localized_route('engagements.manage-access-needs', $engagement))
        ->assertOk();

    $engagement->update(['individual_connector_id' => null, 'organizational_connector_id' => $connectorOrganization->id]);
    $engagement = $engagement->fresh();

    actingAs($connectorOrganizationUser)->get(localized_route('engagements.manage-participants', $engagement))
        ->assertOk()
        ->assertSee('Add participant');

    actingAs($connectorOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement))
        ->assertOk();
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

    $response = actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
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

    $response = actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
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

    $response = actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
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

    $response = actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage-access-needs', $engagement));
    $response->assertOk();
    $response->assertViewHas('otherAccessNeeds');
    expect($response['otherAccessNeeds'])->toEqualCanonicalizing(collect([$otherAccessNeed, $differentOtherAccessNeed]));
});

test('store access needs permissions validation errors', function (array $state, array $errors) {
    $engagement = Engagement::factory()->create(['recruitment' => 'open-call']);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $otherAccessNeed = 'custom access need';
    $user = User::factory()->create();
    $user->individual->update([
        'roles' => ['participant'],
        'region' => 'NS',
        'locality' => 'Bridgewater',
        'other_access_need' => $otherAccessNeed,
    ]);
    $user->individual->paymentTypes()->attach(PaymentType::first());
    $engagement->participants()->save($user->individual, ['status' => 'confirmed']);

    actingAs($user)
        ->post(localized_route('engagements.store-access-needs-permissions', $engagement), $state)
        ->assertSessionHasErrors($errors);
})->with('storeAccessNeedsPermissionsValidationErrors');

test('add organization validation errors', function (array $state, array $errors) {
    $engagement = Engagement::factory()->create([
        'who' => 'organization',
        'recruitment' => 'open-call',
    ]);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    actingAs($regulatedOrganizationUser)
        ->post(localized_route('engagements.add-organization', $engagement), $state)
        ->assertSessionHasErrors($errors);
})->with('addOrganizationValidationErrors');

test('invite participant validation errors', function (array $state, array $errors) {
    $engagement = Engagement::factory()->create(['recruitment' => 'open-call']);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create([
        'email' => 'not-individual@example.com',
        'context' => UserContext::RegulatedOrganization->value,
    ]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $user = User::factory()
        ->hasIndividual(['roles' => ['connector']])
        ->create();

    $engagement->connector()->associate($user->individual);
    $engagement->save();

    // Current participant
    $existing = User::factory()->create(['email' => 'existing@example.com']);
    $existing->individual->update([
        'roles' => ['participant'],
        'region' => 'NS',
        'locality' => 'Bridgewater',
    ]);
    $existing->individual->paymentTypes()->attach(PaymentType::first());
    $engagement->participants()->save($existing->individual, ['status' => 'confirmed']);

    // invited participant
    $existing = User::factory()->create(['email' => 'invited@example.com']);
    $existing->individual->update([
        'roles' => ['participant'],
    ]);

    Invitation::factory()->create([
        'email' => $existing->email,
        'invitationable_id' => $engagement->id,
        'invitationable_type' => Engagement::class,
    ]);

    // Not a consultation participant
    $existing = User::factory()->create(['email' => 'not-participant@example.com']);
    $existing->individual->update([
        'roles' => ['connector'],
    ]);

    actingAs($user)
        ->post(localized_route('engagements.invite-participant', $engagement), $state)
        ->assertSessionHasErrors($errors);
})->with('inviteParticipantValidationErrors');

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

test('users can browse engagements', function () {
    $engagementName = 'Test engagement';
    Engagement::factory()->create(['name' => $engagementName]);

    $user = User::factory()->create();

    actingAs($user)->get(localized_route('engagements.index'))
        ->assertOk()
        ->assertSee($engagementName);
});

test('statuses scope', function () {
    $openEngagement = Engagement::factory()->create([
        'signup_by_date' => Carbon::now()->addDays(5),
    ]);

    $closedEngagement = Engagement::factory()->create([
        'signup_by_date' => Carbon::now()->subDays(5),
    ]);

    $noSignUpDateEngagement = Engagement::factory()->create(['signup_by_date' => null]);

    $statusQuery = Engagement::statuses([EngagementSignUpStatus::Open->value])->get();
    expect($statusQuery->contains($openEngagement))->toBeTrue();
    expect($statusQuery->contains($closedEngagement))->toBeFalse();
    expect($statusQuery->contains($noSignUpDateEngagement))->toBeFalse();

    $statusQuery = Engagement::statuses([EngagementSignUpStatus::Closed->value])->get();
    expect($statusQuery->contains($closedEngagement))->toBeTrue();
    expect($statusQuery->contains($openEngagement))->toBeFalse();
    expect($statusQuery->contains($noSignUpDateEngagement))->toBeFalse();

    $statusQuery = Engagement::statuses(array_column(EngagementSignUpStatus::cases(), 'value'))->get();
    expect($statusQuery->contains($openEngagement))->toBeTrue();
    expect($statusQuery->contains($closedEngagement))->toBeTrue();
    expect($statusQuery->contains($noSignUpDateEngagement))->toBeFalse();

    $statusQuery = Engagement::statuses([])->get();
    expect($statusQuery->contains($openEngagement))->toBeTrue();
    expect($statusQuery->contains($closedEngagement))->toBeTrue();
    expect($statusQuery->contains($noSignUpDateEngagement))->toBeTrue();
});

test('formats scope', function (array $filter = [], array $toSee = [], array $dontSee = []) {
    $toContain = [];
    foreach ($toSee as $format => $name) {
        $toContain[] = Engagement::factory()->create([
            'name' => $name,
            'format' => $format,
        ]);
    }

    $dontContain = [];
    foreach ($dontSee as $format => $name) {
        $dontContain[] = Engagement::factory()->create([
            'name' => $name,
            'format' => $format,
        ]);
    }

    $formatQuery = Engagement::formats($filter)->get();

    foreach ($toContain as $engagement) {
        expect($formatQuery->contains($engagement))->toBeTrue();
    }

    foreach ($dontContain as $engagement) {
        expect($formatQuery->contains($engagement))->toBeFalse();
    }
})->with('browseEngagementsFormat');

test('seekings scope', function () {
    $openCallEngagement = Engagement::factory()->create(['recruitment' => 'open-call']);

    $connectorEngagement = Engagement::factory()->create([
        'recruitment' => 'connector',
        'extra_attributes' => ['seeking_community_connector' => true],
    ]);

    $organizationEngagement = Engagement::factory()->create([
        'recruitment' => 'connector',
        'who' => 'organization',
    ]);

    $seekingQuery = Engagement::seekings([SeekingForEngagement::Participants->value])->get();
    expect($seekingQuery->contains($openCallEngagement))->toBeTrue();
    expect($seekingQuery->contains($connectorEngagement))->toBeFalse();
    expect($seekingQuery->contains($organizationEngagement))->toBeFalse();

    $seekingQuery = Engagement::seekings([SeekingForEngagement::Connectors->value])->get();
    expect($seekingQuery->contains($connectorEngagement))->toBeTrue();
    expect($seekingQuery->contains($openCallEngagement))->toBeFalse();
    expect($seekingQuery->contains($organizationEngagement))->toBeFalse();

    $seekingQuery = Engagement::seekings([SeekingForEngagement::Organizations->value])->get();
    expect($seekingQuery->contains($organizationEngagement))->toBeTrue();
    expect($seekingQuery->contains($connectorEngagement))->toBeFalse();
    expect($seekingQuery->contains($openCallEngagement))->toBeFalse();

    $seekingQuery = Engagement::seekings(array_column(SeekingForEngagement::cases(), 'value'))->get();
    expect($seekingQuery->contains($openCallEngagement))->toBeTrue();
    expect($seekingQuery->contains($connectorEngagement))->toBeTrue();
    expect($seekingQuery->contains($organizationEngagement))->toBeTrue();

    $seekingQuery = Engagement::seekings([])->get();
    expect($seekingQuery->contains($openCallEngagement))->toBeTrue();
    expect($seekingQuery->contains($connectorEngagement))->toBeTrue();
    expect($seekingQuery->contains($organizationEngagement))->toBeTrue();
});

test('initiators scope', function () {
    $communityOrganizationEngagement = Engagement::factory()
        ->for(
            Project::factory()
                ->for(Organization::factory(), 'projectable')
        )
        ->create();

    $regulatedOrganizationEngagement = Engagement::factory()
        ->for(
            Project::factory()
                ->for(RegulatedOrganization::factory(), 'projectable')
        )
        ->create();

    $initiatorQuery = Engagement::initiators([ProjectInitiator::Organization->value])->get();
    expect($initiatorQuery->contains($communityOrganizationEngagement))->toBeTrue();
    expect($initiatorQuery->contains($regulatedOrganizationEngagement))->toBeFalse();

    $initiatorQuery = Engagement::initiators([ProjectInitiator::RegulatedOrganization->value])->get();
    expect($initiatorQuery->contains($regulatedOrganizationEngagement))->toBeTrue();
    expect($initiatorQuery->contains($communityOrganizationEngagement))->toBeFalse();

    $initiatorQuery = Engagement::initiators(array_column(ProjectInitiator::cases(), 'value'))->get();
    expect($initiatorQuery->contains($regulatedOrganizationEngagement))->toBeTrue();
    expect($initiatorQuery->contains($communityOrganizationEngagement))->toBeTrue();

    $initiatorQuery = Engagement::initiators([])->get();
    expect($initiatorQuery->contains($regulatedOrganizationEngagement))->toBeTrue();
    expect($initiatorQuery->contains($communityOrganizationEngagement))->toBeTrue();
});

test('seekingDisabilityAndDeafGroups scope', function () {
    $disabilityTypeDeaf = Identity::factory()->create([
        'name' => [
            'en' => 'Deaf',
            'fr' => __('Deaf', [], 'fr'),
        ],
        'clusters' => ['disability-and-deaf'],
    ]);
    $disabilityTypeDeafEngagement = Engagement::factory()->create();
    $disabilityTypeDeafEngagement->matchingStrategy->identities()->attach($disabilityTypeDeaf);

    $disabilityTypeCognitive = Identity::factory()->create([
        'name' => [
            'en' => 'Cognitive disabilities',
            'fr' => __('Cognitive disabilities', [], 'fr'),
        ],
        'description' => [
            'en' => 'Includes traumatic brain injury, memory difficulties, dementia',
            'fr' => __('Includes traumatic brain injury, memory difficulties, dementia', [], 'fr'),
        ],
        'clusters' => ['disability-and-deaf'],
    ]);
    $disabilityTypeCognitiveEngagement = Engagement::factory()->create();
    $disabilityTypeCognitiveEngagement->matchingStrategy->identities()->attach($disabilityTypeCognitive);

    $seekingGroupQuery = Engagement::seekingDisabilityAndDeafGroups([$disabilityTypeDeaf->id])->get();
    expect($seekingGroupQuery->contains($disabilityTypeDeafEngagement))->toBeTrue();
    expect($seekingGroupQuery->contains($disabilityTypeCognitiveEngagement))->toBeFalse();

    $seekingGroupQuery = Engagement::seekingDisabilityAndDeafGroups([$disabilityTypeCognitive->id])->get();
    expect($seekingGroupQuery->contains($disabilityTypeCognitiveEngagement))->toBeTrue();
    expect($seekingGroupQuery->contains($disabilityTypeDeafEngagement))->toBeFalse();

    $seekingGroupQuery = Engagement::seekingDisabilityAndDeafGroups([$disabilityTypeDeaf->id, $disabilityTypeCognitive->id])->get();
    expect($seekingGroupQuery->contains($disabilityTypeCognitiveEngagement))->toBeTrue();
    expect($seekingGroupQuery->contains($disabilityTypeDeafEngagement))->toBeTrue();

    $seekingGroupQuery = Engagement::seekingDisabilityAndDeafGroups([])->get();
    expect($seekingGroupQuery->contains($disabilityTypeCognitiveEngagement))->toBeTrue();
    expect($seekingGroupQuery->contains($disabilityTypeDeafEngagement))->toBeTrue();
});

test('meetingTypes scope', function () {
    $inPersonInterviewEngagement = Engagement::factory()->create([
        'extra_attributes' => ['format' => 'interviews'],
        'meeting_types' => [MeetingType::InPerson->value],
    ]);

    $virtualWorkshopEngagement = Engagement::factory()
        ->has(Meeting::factory()->state([
            'meeting_types' => [MeetingType::WebConference->value],
        ]))
        ->create([
            'extra_attributes' => ['format' => 'workshop'],
            'meeting_types' => null,
        ]);

    $phoneFocusGroupEngagement = Engagement::factory()
        ->has(Meeting::factory()->state([
            'meeting_types' => [MeetingType::Phone->value],
        ]))
        ->create([
            'extra_attributes' => ['format' => 'focus-group'],
            'meeting_types' => null,
        ]);

    $meetingTypeQuery = Engagement::meetingTypes([MeetingType::InPerson->value])->get();
    expect($meetingTypeQuery->contains($inPersonInterviewEngagement))->toBeTrue();
    expect($meetingTypeQuery->contains($virtualWorkshopEngagement))->toBeFalse();
    expect($meetingTypeQuery->contains($phoneFocusGroupEngagement))->toBeFalse();

    $meetingTypeQuery = Engagement::meetingTypes([MeetingType::WebConference->value])->get();
    expect($meetingTypeQuery->contains($virtualWorkshopEngagement))->toBeTrue();
    expect($meetingTypeQuery->contains($inPersonInterviewEngagement))->toBeFalse();
    expect($meetingTypeQuery->contains($phoneFocusGroupEngagement))->toBeFalse();

    $meetingTypeQuery = Engagement::meetingTypes([MeetingType::Phone->value])->get();
    expect($meetingTypeQuery->contains($phoneFocusGroupEngagement))->toBeTrue();
    expect($meetingTypeQuery->contains($virtualWorkshopEngagement))->toBeFalse();
    expect($meetingTypeQuery->contains($inPersonInterviewEngagement))->toBeFalse();

    $meetingTypeQuery = Engagement::meetingTypes(array_column(MeetingType::cases(), 'value'))->get();
    expect($meetingTypeQuery->contains($inPersonInterviewEngagement))->toBeTrue();
    expect($meetingTypeQuery->contains($virtualWorkshopEngagement))->toBeTrue();
    expect($meetingTypeQuery->contains($phoneFocusGroupEngagement))->toBeTrue();

    $meetingTypeQuery = Engagement::meetingTypes([])->get();
    expect($meetingTypeQuery->contains($inPersonInterviewEngagement))->toBeTrue();
    expect($meetingTypeQuery->contains($virtualWorkshopEngagement))->toBeTrue();
    expect($meetingTypeQuery->contains($phoneFocusGroupEngagement))->toBeTrue();
});

test('compensations scope', function () {
    $paidEngagement = Engagement::factory()->create(['paid' => true]);
    $volunteerEngagement = Engagement::factory()->create(['paid' => false]);

    $compensationQuery = Engagement::compensations([Compensation::Paid->value])->get();
    expect($compensationQuery->contains($paidEngagement))->toBeTrue();
    expect($compensationQuery->contains($volunteerEngagement))->toBeFalse();

    $compensationQuery = Engagement::compensations([Compensation::Volunteer->value])->get();
    expect($compensationQuery->contains($volunteerEngagement))->toBeTrue();
    expect($compensationQuery->contains($paidEngagement))->toBeFalse();

    $compensationQuery = Engagement::compensations(array_column(Compensation::cases(), 'value'))->get();
    expect($compensationQuery->contains($volunteerEngagement))->toBeTrue();
    expect($compensationQuery->contains($paidEngagement))->toBeTrue();

    $compensationQuery = Engagement::compensations([])->get();
    expect($compensationQuery->contains($volunteerEngagement))->toBeTrue();
    expect($compensationQuery->contains($paidEngagement))->toBeTrue();
});

test('sectors scope', function () {
    $privateSector = Sector::factory()->create([
        'name' => [
            'en' => 'Federally Regulated private sector',
            'fr' => __('Federally Regulated private sector', [], 'fr'),
        ],
        'description' => [
            'en' => 'Banks, federal transportation network (airlines, rail, road and marine transportation providers that cross provincial or international borders), atomic energy, postal and courier services, the broadcasting and telecommunications sectors',
            'fr' => __('Banks, federal transportation network (airlines, rail, road and marine transportation providers that cross provincial or international borders), atomic energy, postal and courier services, the broadcasting and telecommunications sectors', [], 'fr'),
        ],
    ]);

    $privateSectorEngagement = Engagement::factory()->create();
    $privateSectorEngagement->project->projectable->sectors()->attach($privateSector);

    $parliamentarySector = Sector::factory()->create([
        'name' => [
            'en' => 'Parliamentary entities',
            'fr' => __('Parliamentary entities', [], 'fr'),
        ],
        'description' => [
            'en' => 'House of Commons, Senate, Library of Parliament, Parliamentary Protective Service',
            'fr' => __('House of Commons, Senate, Library of Parliament, Parliamentary Protective Service', [], 'fr'),
        ],
    ]);

    $parliamentarySectorEngagement = Engagement::factory()->create();
    $parliamentarySectorEngagement->project->projectable->sectors()->attach($parliamentarySector);

    $sectorQuery = Engagement::sectors([$privateSector->id])->get();
    expect($sectorQuery->contains($privateSectorEngagement))->toBeTrue();
    expect($sectorQuery->contains($parliamentarySectorEngagement))->toBeFalse();

    $sectorQuery = Engagement::sectors([$parliamentarySector->id])->get();
    expect($sectorQuery->contains($parliamentarySectorEngagement))->toBeTrue();
    expect($sectorQuery->contains($privateSectorEngagement))->toBeFalse();

    $sectorQuery = Engagement::sectors([$privateSector->id, $parliamentarySector->id])->get();
    expect($sectorQuery->contains($privateSectorEngagement))->toBeTrue();
    expect($sectorQuery->contains($parliamentarySectorEngagement))->toBeTrue();

    $sectorQuery = Engagement::sectors([])->get();
    expect($sectorQuery->contains($privateSectorEngagement))->toBeTrue();
    expect($sectorQuery->contains($parliamentarySectorEngagement))->toBeTrue();
});

test('areas of impact scope', function () {
    $employmentImpact = Impact::factory()->create([
        'name' => [
            'en' => 'Employment',
            'fr' => __('Employment', [], 'fr'),
        ],
    ]);
    $employmentImpactEngagement = Engagement::factory()->create();
    $employmentImpactEngagement->project->impacts()->attach($employmentImpact);

    $communicationImpact = Impact::factory()->create([
        'name' => [
            'en' => 'Communications',
            'fr' => __('Communications', [], 'fr'),
        ],
    ]);
    $communicationImpactEngagement = Engagement::factory()->create();
    $communicationImpactEngagement->project->impacts()->attach($communicationImpact);

    $impactQuery = Engagement::areasOfImpact([$employmentImpact->id])->get();
    expect($impactQuery->contains($employmentImpactEngagement))->toBeTrue();
    expect($impactQuery->contains($communicationImpactEngagement))->toBeFalse();

    $impactQuery = Engagement::areasOfImpact([$communicationImpact->id])->get();
    expect($impactQuery->contains($communicationImpactEngagement))->toBeTrue();
    expect($impactQuery->contains($employmentImpactEngagement))->toBeFalse();

    $impactQuery = Engagement::areasOfImpact([$employmentImpact->id, $communicationImpact->id])->get();
    expect($impactQuery->contains($employmentImpactEngagement))->toBeTrue();
    expect($impactQuery->contains($communicationImpactEngagement))->toBeTrue();

    $impactQuery = Engagement::areasOfImpact([])->get();
    expect($impactQuery->contains($employmentImpactEngagement))->toBeTrue();
    expect($impactQuery->contains($communicationImpactEngagement))->toBeTrue();
});

test('recruitment methods scope', function () {
    $openCallEngagement = Engagement::factory()->create([
        'recruitment' => EngagementRecruitment::OpenCall->value,
    ]);

    $connectorEngagement = Engagement::factory()->create([
        'recruitment' => EngagementRecruitment::CommunityConnector->value,
    ]);

    $recruitmentMethodQuery = Engagement::recruitmentMethods([EngagementRecruitment::OpenCall->value])->get();
    expect($recruitmentMethodQuery->contains($openCallEngagement))->toBeTrue();
    expect($recruitmentMethodQuery->contains($connectorEngagement))->toBeFalse();

    $recruitmentMethodQuery = Engagement::recruitmentMethods([EngagementRecruitment::CommunityConnector->value])->get();
    expect($recruitmentMethodQuery->contains($connectorEngagement))->toBeTrue();
    expect($recruitmentMethodQuery->contains($openCallEngagement))->toBeFalse();

    $recruitmentMethodQuery = Engagement::recruitmentMethods(array_column(EngagementRecruitment::cases(), 'value'))->get();
    expect($recruitmentMethodQuery->contains($connectorEngagement))->toBeTrue();
    expect($recruitmentMethodQuery->contains($openCallEngagement))->toBeTrue();

    $recruitmentMethodQuery = Engagement::recruitmentMethods([])->get();
    expect($recruitmentMethodQuery->contains($connectorEngagement))->toBeTrue();
    expect($recruitmentMethodQuery->contains($openCallEngagement))->toBeTrue();
});

test('locations scope', function () {
    $regionSpecificEngagement = Engagement::factory()->create();
    $regionSpecificEngagement->matchingStrategy->update([
        'regions' => ['AB'],
    ]);

    $locationSpecificEngagement = Engagement::factory()->create();
    $locationSpecificEngagement->matchingStrategy->update([
        'locations' => [
            ['region' => 'AB', 'locality' => 'Edmonton'],
            ['region' => 'ON', 'locality' => 'Toronto'],
        ],
    ]);

    $locationQuery = Engagement::locations(['AB'])->get();
    expect($locationQuery->contains($regionSpecificEngagement))->toBeTrue();
    expect($locationQuery->contains($locationSpecificEngagement))->toBeTrue();

    $locationQuery = Engagement::locations(['ON'])->get();
    expect($locationQuery->contains($regionSpecificEngagement))->toBeFalse();
    expect($locationQuery->contains($locationSpecificEngagement))->toBeTrue();

    $locationQuery = Engagement::locations(['AB', 'ON'])->get();
    expect($locationQuery->contains($regionSpecificEngagement))->toBeTrue();
    expect($locationQuery->contains($locationSpecificEngagement))->toBeTrue();

    $locationQuery = Engagement::locations([])->get();
    expect($locationQuery->contains($regionSpecificEngagement))->toBeTrue();
    expect($locationQuery->contains($locationSpecificEngagement))->toBeTrue();
});
