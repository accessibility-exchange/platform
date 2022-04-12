<?php

namespace Tests\Feature;

use App\Models\CommunityMember;
use App\Models\Impact;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\ImpactSeeder;

test('users with organization or regulated organization admin role can create projects', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('projects.create'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'new',
    ]);

    $response->assertSessionHas('context', 'new');

    $response = $this->actingAs($user)->post(localized_route('projects.store-focus'), [
        'focus' => 'engage',
    ]);

    $response->assertSessionHas('focus', 'engage');

    $response = $this->actingAs($user)->post(localized_route('projects.store-languages'), [
        'languages' => ['en', 'fr', 'ase', 'fcs'],
    ]);

    $response->assertSessionHas('languages', ['en', 'fr', 'ase', 'fcs']);

    $response = $this->actingAs($user)->post(localized_route('projects.store'), [
        'projectable_id' => $regulatedOrganization->id,
        'projectable_type' => 'App\Models\RegulatedOrganization',
        'name' => ['en' => 'Test Project'],
        'start_date' => '2022-04-01',
        'goals' => ['en' => 'Here’s a brief description of what we hope to accomplish in this consultation process.'],
        'scope' => ['en' => 'The outcomes of this project will impact existing and new customers who identify as having a disability, or who are support people for someone with a disability.'],
    ]);

    $project = Project::all()->first();
    $url = localized_route('projects.edit', ['project' => $project, 'step' => 2]);

    $response->assertSessionHasNoErrors();

    $this->assertEquals($project->name, 'Test Project');

    $response->assertRedirect($url);

    $previous_project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'follow-up',
        'ancestor' => $previous_project->id,
    ]);
    $response->assertSessionHas('context', 'follow-up');
    $response->assertSessionHas('ancestor', $previous_project->id);
});

test('users without regulated organization admin role cannot create projects', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('projects.create'));
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->get(localized_route('projects.create'));
    $response->assertForbidden();
});

test('projects can be published and unpublished', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'publish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.show', $project));
    $this->assertTrue($project->checkStatus('published'));

    $response = $this->actingAs($user)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'unpublish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.show', $project));

    $project = $project->fresh();

    $this->assertTrue($project->checkStatus('draft'));

    $response = $this->actingAs($user)->get(localized_route('projects.show', $project));
    $response->assertSee('draft');
});

test('users can view projects', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('projects.index'));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('projects.show', $project));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('projects.show-team', $project));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('projects.show-engagements', $project));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('projects.show-outcomes', $project));
    $response->assertOk();
});

test('community members can express interest in projects', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->followingRedirects()->from(localized_route('projects.show', $project))->post(localized_route('community-members.express-interest', $communityMember), ['project_id' => $project->id]);
    $response->assertSee('You have expressed your interest in this project.');
    $response->assertOk();

    $response = $this->actingAs($user)->followingRedirects()->from(localized_route('projects.show', $project))->post(localized_route('community-members.remove-interest', $communityMember), ['project_id' => $project->id]);
    $response->assertSee('You have removed your expression of interest in this project.');
    $response->assertOk();
});

test('guests cannot view projects', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->get(localized_route('projects.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('projects.show', $project));
    $response->assertRedirect(localized_route('login'));
});

test('users with regulated organization admin role can edit projects', function () {
    $this->seed(ImpactSeeder::class);

    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('projects.edit', $project));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('projects.update', $project), [
        'name' => ['en' => $project->name],
        'goals' => ['en' => 'Some new goals'],
        'scope' => ['en' => $project->scope],
        'impacts' => [Impact::first()->id],
        'start_date' => $project->start_date,
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));

    $project = $project->fresh();
    $this->assertEquals(count($project->impacts), 1);

    $response = $this->actingAs($user)->put(localized_route('projects.update', $project), [
        'name' => ['en' => $project->name],
        'goals' => ['en' => 'Some newer goals'],
        'scope' => ['en' => $project->scope],
        'start_date' => $project->start_date,
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 2]));

    $response = $this->actingAs($user)->put(localized_route('projects.update-team', $project), [
        'team_count' => '42',
        'team_languages' => ['en'],
        'has_consultant' => false,
        'save_and_previous' => __('Save and previous'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));
});

test('users without regulated organization admin role cannot edit projects', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('projects.edit', $project));
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('projects.update', $project), [
        'name' => 'My updated project name',
    ]);
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->get(localized_route('projects.edit', $project));
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->put(localized_route('projects.update', $project), [
        'name' => 'My updated project name',
    ]);
    $response->assertForbidden();
});

test('users with regulated organization admin role can manage projects', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('projects.manage', $project));
    $response->assertOk();
});

test('users without regulated organization admin role cannot manage projects', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('projects.manage', $project));
    $response->assertForbidden();
});

test('users with regulated organization admin role can delete projects', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('projects.edit', $project));
    $response->assertOk();

    $response = $this->actingAs($user)->from(localized_route('projects.edit', $project))->delete(localized_route('projects.destroy', $project), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('dashboard'));
});

test('users without regulated organization admin role cannot delete projects', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('projects.destroy', $project), [
        'current_password' => 'password',
    ]);
    $response->assertForbidden();

    $response = $this->actingAs($other_user)->from(localized_route('dashboard'))->delete(localized_route('projects.destroy', $project), [
        'current_password' => 'password',
    ]);
    $response->assertForbidden();
});

test('projects have timeframes', function () {
    $organization = Organization::factory()->create();
    $org_past_project = Project::factory()->create([
        'projectable_type' => 'App\Models\Organization',
        'projectable_id' => $organization->id,
        'start_date' => '2020-01-01',
        'end_date' => '2020-12-31',
    ]);
    $org_past_project_multi_year = Project::factory()->create([
        'projectable_type' => 'App\Models\Organization',
        'projectable_id' => $organization->id,
        'start_date' => '2020-01-01',
        'end_date' => '2021-12-31',
    ]);
    $org_current_project = Project::factory()->create([
        'projectable_type' => 'App\Models\Organization',
        'projectable_id' => $organization->id,
        'start_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
    ]);
    $org_future_project = Project::factory()->create([
        'projectable_type' => 'App\Models\Organization',
        'projectable_id' => $organization->id,
        'start_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
    ]);

    $this->assertStringContainsString('January&ndash;December 2020', $org_past_project->timeframe());
    $this->assertStringContainsString('January 2020&ndash;December 2021', $org_past_project_multi_year->timeframe());
    $this->assertStringContainsString('Started', $org_current_project->timeframe());
    $this->assertStringContainsString('Starting', $org_future_project->timeframe());

    $this->assertEquals(4, count($organization->projects));
    $this->assertEquals(2, count($organization->pastProjects));
    $this->assertEquals(1, count($organization->currentProjects));
    $this->assertEquals(1, count($organization->futureProjects));

    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $regulated_org_past_project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
        'start_date' => '2020-01-01',
        'end_date' => '2020-12-31',
    ]);
    $regulated_org_past_project_multi_year = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
        'start_date' => '2020-01-01',
        'end_date' => '2021-12-31',
    ]);
    $regulated_org_current_project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
        'start_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
    ]);
    $regulated_org_future_project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
        'start_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
    ]);

    $this->assertStringContainsString('January&ndash;December 2020', $regulated_org_past_project->timeframe());
    $this->assertStringContainsString('January 2020&ndash;December 2021', $regulated_org_past_project_multi_year->timeframe());
    $this->assertStringContainsString('Started', $regulated_org_current_project->timeframe());
    $this->assertStringContainsString('Starting', $regulated_org_future_project->timeframe());

    $this->assertEquals(4, count($regulatedOrganization->projects));
    $this->assertEquals(2, count($regulatedOrganization->pastProjects));
    $this->assertEquals(1, count($regulatedOrganization->currentProjects));
    $this->assertEquals(1, count($regulatedOrganization->futureProjects));
});

test('projects reflect consultant origin', function () {
    $community_member = CommunityMember::factory()->create();

    $project_with_external_consultant = Project::factory()->create([
        'has_consultant' => true,
        'consultant_name' => 'Joe Consultant',
    ]);

    $project_with_platform_consultant = Project::factory()->create([
        'has_consultant' => true,
        'consultant_id' => $community_member->id,
    ]);

    $this->assertEquals('external', $project_with_external_consultant->consultant_origin());
    $this->assertEquals('platform', $project_with_platform_consultant->consultant_origin());
    $this->assertEquals($community_member->id, $project_with_platform_consultant->accessibilityConsultant->id);
});

test('projects reflect team experience', function () {
    $project = Project::factory()->create([
        'team_has_disability_or_deaf_lived_experience' => true,
    ]);

    $this->assertEquals('Our team includes people with disabilities and/or Deaf people.', $project->teamExperience());

    $project->update([
        'team_has_other_lived_experience' => true,
    ]);

    $project = $project->fresh();

    $this->assertEquals('Our team includes people with disabilities and/or Deaf people as well as people from other equity-seeking groups.', $project->teamExperience());

    $project->update([
        'team_has_disability_or_deaf_lived_experience' => false,
    ]);

    $project = $project->fresh();

    $this->assertEquals('Our team includes people from equity-seeking groups.', $project->teamExperience());

    $project->update([
        'team_has_other_lived_experience' => false,
    ]);

    $project = $project->fresh();

    $this->assertEquals('Our team does not include people with disabilities and/or Deaf people or people from other equity-seeking groups.', $project->teamExperience());
});

test('project retrieves team trainings properly', function () {
    $project = Project::factory()->create([
        'team_trainings' => [
            ['name' => 'Example Training', 'date' => '2022-04-01', 'trainer_name' => 'Acme Training Co.', 'trainer_url' => 'https://acme.training'],
        ],
    ]);

    expect($project->team_trainings[0]['date'])->toEqual('April 2022');

    $projectWithNullTrainings = Project::factory()->create([
        'team_trainings' => [
            ['name' => '', 'date' => '', 'trainer_name' => '', 'trainer_url' => ''],
        ],
    ]);

    expect($projectWithNullTrainings->team_trainings)->toBeEmpty();
});
