<?php

use App\Models\Engagement;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;

test('users with regulated organization admin role can create engagements', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('engagements.create', $project));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('engagements.create', $project), [
        'project_id' => $project->id,
        'name' => ['en' => 'Test Engagement'],
        'goals' => ['en' => 'This is what we want to do.'],
        'recruitment' => 'automatic',
    ]);

    $engagement = Engagement::where('name->en', 'Test Engagement')->get()->first();

    $url = localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement->id]);

    $response->assertSessionHasNoErrors();

    $response->assertRedirect($url);
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

    $response = $this->actingAs($user)->get(localized_route('engagements.show', ['project' => $engagement->project, 'engagement' => $engagement->id]));
    $response->assertOk();
});

test('guests cannot view engagements', function () {
    $engagement = Engagement::factory()->create();

    $response = $this->get(localized_route('engagements.show', ['project' => $engagement->project, 'engagement' => $engagement->id]));
    $response->assertRedirect(localized_route('login'));
});

test('users with regulated organization admin role can edit engagements', function () {
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

    $response = $this->actingAs($user)->get(localized_route('engagements.edit', ['project' => $project, 'engagement' => $engagement]));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]), [
        'name' => ['en' => 'My renamed engagement'],
        'recruitment' => 'automatic',
    ]);

    $updated_engagement = Engagement::where('name->en', 'My renamed engagement')->first();

    $response->assertRedirect(localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement]));
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

    $response = $this->actingAs($user)->get(localized_route('engagements.edit', ['project' => $project, 'engagement' => $engagement]));
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]), [
        'name' => ['en' => 'My renamed engagement'],
    ]);
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->get(localized_route('engagements.edit', ['project' => $project, 'engagement' => $engagement]));
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->put(localized_route('engagements.update', ['project' => $project, 'engagement' => $engagement]), [
        'name' => ['en' => 'My renamed engagement'],
    ]);
    $response->assertForbidden();
});

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

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement]));
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

    $response = $this->actingAs($user)->get(localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement]));
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->get(localized_route('engagements.manage', ['project' => $project, 'engagement' => $engagement]));
    $response->assertForbidden();
});

test('engagement participants can participate in engagements', function () {
    $user = User::factory()->create();
    $participant = $user->communityMember;
    $engagement = Engagement::factory()->create();
    $engagement->participants()->attach($participant->id, ['status' => 'confirmed']);

    $this->assertTrue($engagement->participants->isNotEmpty());
    $this->assertTrue($engagement->confirmedParticipants->isNotEmpty());

    $response = $this->actingAs($user)->get(localized_route('engagements.participate', ['project' => $engagement->project, 'engagement' => $engagement]));
    $response->assertOk();
});
