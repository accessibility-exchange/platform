<?php

use App\Http\Requests\StoreEngagementRequest;
use App\Http\Requests\UpdateEngagementRequest;
use App\Http\Requests\UpdateEngagementSelectionCriteriaRequest;
use App\Models\DisabilityType;
use App\Models\Engagement;
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

    $response->assertRedirect(localized_route('engagements.show-outreach-selection', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.show-outreach-selection', $engagement));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('engagements.store-outreach', $engagement), [
        'who' => 'individuals',
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

    $response = $this->actingAs($user)->get(localized_route('engagements.show-criteria-selection', $engagement));
    $response->assertOk();

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create();

    $response = $this->actingAs($user)->put(localized_route('engagements.store-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->put(localized_route('engagements.store-outreach', $engagement), [
        'who' => 'organization',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));
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

    $response = $this->actingAs($user)->get(localized_route('engagements.show-criteria-selection', $engagement));
    $response->assertOk();

    $data = UpdateEngagementSelectionCriteriaRequest::factory()->create([
        'cross_disability' => 0,
        'disability_types' => [
            DisabilityType::where('name->en', 'Deaf')->first()->id,
            DisabilityType::where('name->en', 'Hard-of-hearing')->first()->id,
        ],
    ]);

    $response = $this->actingAs($user)->put(localized_route('engagements.store-criteria', $engagement), $data);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage', $engagement));

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', $engagement));
    $response->assertSee('<p>Deaf', false);
    $response->assertSee('Hard-of-hearing</p>', false);

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
