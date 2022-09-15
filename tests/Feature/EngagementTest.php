<?php

use App\Http\Requests\StoreEngagementRequest;
use App\Http\Requests\UpdateEngagementRequest;
use App\Http\Requests\UpdateEngagementSelectionCriteriaRequest;
use App\Models\AgeBracket;
use App\Models\AreaType;
use App\Models\DisabilityType;
use App\Models\Engagement;
use App\Models\EthnoracialIdentity;
use App\Models\IndigenousIdentity;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DisabilityTypeSeeder;

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
        'languages' => ['en', 'fr', 'ase', 'fcs'],
    ]);
    $response->assertRedirect(localized_route('engagements.create', $project));
    $response->assertSessionHas('languages', ['en', 'fr', 'ase', 'fcs']);

    $response = $this->actingAs($user)->get(localized_route('engagements.create', $project));
    $response->assertOk();

    $data = StoreEngagementRequest::factory()->create([
        'project_id' => $project->id,
    ]);

    $response = $this->withSession([
        'languages' => ['en', 'fr', 'ase', 'fcs'],
    ])->actingAs($user)->post(localized_route('engagements.store', $project), $data);

    $response->assertSessionHasNoErrors();

    $engagement = Engagement::where('name->en', $data['name']['en'])->first();

    $response->assertRedirect(localized_route('engagements.show-format-selection', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.show-format-selection', $engagement));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('engagements.store-format', $engagement), [
        'format' => 'survey',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.show-recruitment-selection', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.show-recruitment-selection', $engagement));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('engagements.store-recruitment', $engagement), [
        'recruitment' => 'open-call',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.show-criteria-selection', $engagement));

    expect($engagement->matchingStrategy->location_summary)->toEqual('All provinces and territories');
    expect($engagement->matchingStrategy->disability_and_deaf_group_summary)->toEqual('Cross disability (includes people with disabilities, Deaf people, and supporters)');
    expect($engagement->matchingStrategy->other_identities_summary)->toEqual('Intersectional');

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
        'languages' => ['en', 'fr', 'ase', 'fcs'],
    ])->actingAs($user)->post(localized_route('engagements.store', $project), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', Engagement::where('name->en', $data['name'])->first()));
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

    $response = $this->actingAs($user)->get(localized_route('engagements.edit-criteria', $engagement));
    $response->assertOk();

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'location_type' => 'localities',
        'regions' => $engagement->matchingStrategy->regions ?? [],
        'locations' => [
            [
                'region' => 'NS',
                'locality' => 'Halifax',
            ],
        ],
        'cross_disability' => 0,
        'disability_types' => [
            DisabilityType::where('name->en', 'Hard-of-hearing')->first()->id,
        ],
        'intersectional' => 0,
        'other_identity_type' => 'age-bracket',
        'age_brackets' => [AgeBracket::where('name->en', 'Older people (65+)')->first()->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('Halifax, Nova Scotia');
    $response->assertSee('Hard-of-hearing');
    $response->assertSee('Older people (65+)');

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => 'gender-and-sexual-identity',
        'gender_and_sexual_identities' => ['women', 'nb-gnc-fluid-people', 'trans-people', '2slgbtqiaplus-people'],
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));
    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));

    $response->assertSee('Women');
    $response->assertSee('Non-binary people');
    $response->assertSee('Gender non-conforming people');
    $response->assertSee('Gender fluid people');
    $response->assertSee('Trans people');
    $response->assertSee('2SLGBTQIA+ people');

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => 'indigenous-identity',
        'indigenous_identities' => IndigenousIdentity::all()->pluck('id')->toArray(),
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('First Nations');
    $response->assertSee('Métis');
    $response->assertSee('Inuit');

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => 'ethnoracial-identity',
        'ethnoracial_identities' => EthnoracialIdentity::all()->pluck('id')->toArray(),
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('Black');
    $response->assertSee('South Asian');
    $response->assertSee('Latin American');

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => 'refugee-or-immigrant',
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('Refugees and/or immigrants');

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => 'first-language',
        'first_languages' => ['fr', 'fcs'],
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('French');
    $response->assertSee('Quebec Sign Language');

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'intersectional' => 0,
        'other_identity_type' => 'area-type',
        'area_types' => AreaType::all()->pluck('id')->toArray(),
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.update-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('Urban');
    $response->assertSee('Rural');
    $response->assertSee('Remote');

    $response = $this->actingAs($user)->get(localized_route('engagements.edit', $engagement));
    $response->assertOk();

    $data = UpdateEngagementRequest::factory()->create();

    $response = $this->actingAs($user)->put(localized_route('engagements.update', $engagement), $data);

    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    expect($engagement->fresh()->description)->toEqual($data['description']['en']);

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

test('users with regulated organization admin role can manage engagements', function () {
    $this->seed(DisabilityTypeSeeder::class);

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
        'context' => 'regulated-organization',
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

test('engagement participants can participate in engagements', function () {
    $user = User::factory()->create();
    $participant = $user->individual;
    $engagement = Engagement::factory()->create();
    $engagement->participants()->attach($participant->id, ['status' => 'confirmed']);

    $this->assertTrue($engagement->participants->isNotEmpty());
    $this->assertTrue($engagement->confirmedParticipants->isNotEmpty());

    $response = $this->actingAs($user)->get(localized_route('engagements.participate', $engagement));
    $response->assertOk();
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

test('engagements are only publishable if required fields are completed', function () {
    $engagement = Engagement::factory()->create();

    expect($engagement->isPublishable())->toBeFalse();

    $engagement->update(['signup_by_date' => now()]);

    $engagement = $engagement->fresh();

    expect($engagement->isPublishable())->toBeTrue();
});

test('individual user can accept invitation to an engagement as a connector', function () {
    $this->seed(DisabilityTypeSeeder::class);

    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $user = User::factory()->create();
    $user->individual->update(['roles' => ['connector']]);
    $user->individual->publish();
    $individual = $user->individual->fresh();

    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => 'connector',
        'type' => 'individual',
        'email' => $individual->user->email,
    ]);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage', $engagement));
    $response->assertOk();
    $response->assertSee($individual->name);

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);

    $response = $this->actingAs(User::factory()->create())->get($acceptUrl);
    $response->assertForbidden();

    $response = $this->actingAs($individual->user)->get($acceptUrl);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $engagement = $engagement->fresh();

    expect($engagement->connector->id)->toEqual($individual->id);
});

test('individual user can decline invitation to an engagement as a connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $user = User::factory()->create();
    $user->individual->update(['roles' => ['connector']]);
    $user->individual->publish();
    $individual = $user->individual->fresh();

    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => 'connector',
        'type' => 'individual',
        'email' => $individual->user->email,
    ]);

    $response = $this->actingAs(User::factory()->create())->delete(route('contractor-invitations.decline', $invitation));
    $response->assertForbidden();

    $response = $this->actingAs($individual->user)->delete(route('contractor-invitations.decline', $invitation));
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $this->assertModelMissing($invitation);
});

test('organization user can accept invitation to an engagement as a connector', function () {
    $this->seed(DisabilityTypeSeeder::class);

    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $organization = Organization::factory()->create(['roles' => ['consultant'], 'published_at' => now()]);

    $organizationUser = User::factory()->create(['context' => 'organization']);

    $organization->users()->attach(
        $organizationUser,
        ['role' => 'admin']
    );

    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => 'connector',
        'type' => 'organization',
        'email' => $organization->contact_person_email,
    ]);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('engagements.manage', $engagement));
    $response->assertOk();
    $response->assertSee($organization->name);

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);

    $response = $this->actingAs(User::factory()->create())->get($acceptUrl);
    $response->assertForbidden();

    $response = $this->actingAs($organizationUser)->get($acceptUrl);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $engagement = $engagement->fresh();

    expect($engagement->organizationalConnector->id)->toEqual($organization->id);
});

test('organization user can decline invitation to an engagement as a connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $organization = Organization::factory()->create(['roles' => ['consultant'], 'published_at' => now()]);

    $organizationUser = User::factory()->create(['context' => 'organization']);

    $organization->users()->attach(
        $organizationUser,
        ['role' => 'admin']
    );

    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => 'connector',
        'type' => 'organization',
        'email' => $organization->contact_person_email,
    ]);

    $response = $this->actingAs(User::factory()->create())->delete(route('contractor-invitations.decline', $invitation));
    $response->assertForbidden();

    $response = $this->actingAs($organizationUser)->delete(route('contractor-invitations.decline', $invitation));
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $this->assertModelMissing($invitation);
});
