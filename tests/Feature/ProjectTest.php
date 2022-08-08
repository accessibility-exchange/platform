<?php

use App\Models\Impact;
use App\Models\Individual;
use App\Models\IndividualRole;
use App\Models\Organization;
use App\Models\OrganizationRole;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\IndividualRoleSeeder;
use Database\Seeders\OrganizationRoleSeeder;

test('users with organization or regulated organization admin role can create projects', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    expect($user->projects())->toHaveCount(0);

    $response = $this->actingAs($user)->get(localized_route('projects.show-context-selection'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'new',
    ]);

    $response->assertSessionMissing('ancestor');

    $response = $this->actingAs($user)->get(localized_route('projects.create'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('projects.store-languages'), [
        'languages' => ['en', 'fr', 'ase', 'fcs'],
    ]);

    $response->assertSessionHas('languages', ['en', 'fr', 'ase', 'fcs']);

    $response = $this->actingAs($user)->post(localized_route('projects.store'), [
        'projectable_id' => $regulatedOrganization->id,
        'projectable_type' => 'App\Models\RegulatedOrganization',
        'name' => ['en' => 'Test Project'],
    ]);

    $project = Project::where('name->en', 'Test Project')->first();
    $url = localized_route('projects.edit', ['project' => $project, 'step' => 1]);

    $response->assertSessionHasNoErrors();

    $this->assertEquals('Test Project', $project->name);

    $response->assertRedirect($url);

    $user = $user->fresh();

    expect($user->projects())->toHaveCount(1);

    $previous_project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'follow-up',
        'ancestor' => $previous_project->id,
    ]);
    $response->assertSessionHas('ancestor', $previous_project->id);

    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('projects.create'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'new',
    ]);

    $response->assertSessionMissing('ancestor');

    $response = $this->actingAs($user)->get(localized_route('projects.show-language-selection'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('projects.store-languages'), [
        'languages' => ['en', 'fr', 'ase', 'fcs'],
    ]);

    $response->assertSessionHas('languages', ['en', 'fr', 'ase', 'fcs']);

    $response = $this->actingAs($user)->post(localized_route('projects.store'), [
        'projectable_id' => $organization->id,
        'projectable_type' => 'App\Models\Organization',
        'name' => ['en' => 'Test Project 2'],
        'start_date' => '2022-04-01',
        'goals' => ['en' => 'Here’s a brief description of what we hope to accomplish in this consultation process.'],
        'scope' => ['en' => 'The outcomes of this project will impact existing and new customers who identify as having a disability, or who are support people for someone with a disability.'],
    ]);

    $project = Project::where('name->en', 'Test Project 2')->first();
    $url = localized_route('projects.edit', ['project' => $project, 'step' => 1]);

    $response->assertSessionHasNoErrors();

    $this->assertEquals('Test Project 2', $project->name);

    $response->assertRedirect($url);

    $user = $user->fresh();

    expect($user->projects())->toHaveCount(1);

    $previous_project = Project::factory()->create([
        'projectable_id' => $organization->id,
    ]);

    $response = $this->actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'follow-up',
        'ancestor' => $previous_project->id,
    ]);
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
    $adminUser = User::factory()->create(['context' => 'regulated-organization']);
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($adminUser, ['role' => 'admin'])
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    expect($project->isPublishable())->toBeTrue();

    $response = $this->actingAs($user)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'publish' => true,
    ]);

    $response->assertForbidden();

    $response = $this->actingAs($adminUser)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'publish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.show', $project));
    $this->assertTrue($project->checkStatus('published'));

    $response = $this->actingAs($user)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'unpublish' => true,
    ]);

    $response->assertForbidden();

    $response = $this->actingAs($adminUser)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'unpublish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.show', $project));

    $project = $project->fresh();

    $this->assertTrue($project->checkStatus('draft'));

    $response = $this->actingAs($adminUser)->get(localized_route('projects.show', $project));
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

test('individuals can express interest in projects', function () {
    $user = User::factory()->create();
    $individual = Individual::factory()->create([
        'user_id' => $user->id,
    ]);
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->actingAs($user)->followingRedirects()->from(localized_route('projects.show', $project))->post(localized_route('individuals.express-interest', $individual), ['project_id' => $project->id]);
    $response->assertSee('You have expressed your interest in this project.');
    $response->assertOk();

    $response = $this->actingAs($user)->followingRedirects()->from(localized_route('projects.show', $project))->post(localized_route('individuals.remove-interest', $individual), ['project_id' => $project->id]);
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
        'regions' => ['AB', 'BC'],
        'impacts' => [Impact::first()->id],
        'start_date' => $project->start_date,
        'end_date' => $project->end_date,
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
        'regions' => ['AB', 'BC'],
        'impacts' => [Impact::first()->id],
        'start_date' => $project->start_date,
        'end_date' => $project->end_date,
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 2]));

    $response = $this->actingAs($user)->put(localized_route('projects.update-team', $project), [
        'team_count' => '42',
        'team_languages' => ['en'],
        'contact_person_email' => 'me@here.com',
        'contact_person_name' => 'Jonny Appleseed',
        'preferred_contact_method' => 'email',
        'contact_person_response_time' => ['en' => 'ASAP'],
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
        'end_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
    ]);
    $org_future_project = Project::factory()->create([
        'projectable_type' => 'App\Models\Organization',
        'projectable_id' => $organization->id,
        'start_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
        'end_date' => Carbon::now()->addMonths(3)->format('Y-m-d'),
    ]);
    $indeterminate_project = Project::factory()->create([
        'projectable_type' => 'App\Models\Organization',
        'projectable_id' => $organization->id,
        'start_date' => null,
    ]);

    expect($org_past_project)->finished()->toBeTrue();
    expect($org_current_project->finished())->toBeFalse();
    expect($indeterminate_project->finished())->toBeFalse();
    expect($org_current_project->started())->toBeTrue();
    expect($org_future_project->started())->toBeFalse();
    expect($indeterminate_project)->started()->toBeFalse();

    $this->assertStringContainsString('January&ndash;December 2020', $org_past_project->timeframe());
    $this->assertStringContainsString('January 2020&ndash;December 2021', $org_past_project_multi_year->timeframe());

    $this->assertEquals(5, count($organization->projects));
    $this->assertEquals(2, count($organization->completedProjects));
    $this->assertEquals(1, count($organization->inProgressProjects));
    $this->assertEquals(1, count($organization->upcomingProjects));

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

    $this->assertEquals(4, count($regulatedOrganization->projects));
    $this->assertEquals(2, count($regulatedOrganization->completedProjects));
    $this->assertEquals(1, count($regulatedOrganization->inProgressProjects));
    $this->assertEquals(1, count($regulatedOrganization->upcomingProjects));
});

test('projects reflect consultant origin', function () {
    $individual = Individual::factory()->create();

    $project_with_external_consultant = Project::factory()->create([
        'consultant_name' => 'Joe Consultant',
    ]);

    $project_with_platform_consultant = Project::factory()->create([
        'individual_consultant_id' => $individual->id,
    ]);

    $this->assertEquals('external', $project_with_external_consultant->consultant_origin);
    $this->assertEquals('platform', $project_with_platform_consultant->consultant_origin);
    $this->assertEquals($individual->id, $project_with_platform_consultant->consultant->id);
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

test('registered users can access my projects page', function () {
    $this->seed(IndividualRoleSeeder::class);
    $this->seed(OrganizationRoleSeeder::class);

    $individualConsultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();
    $individualParticipantRole = IndividualRole::where('name->en', 'Consultation Participant')->first();

    $individualUser = User::factory()->create();
    $individual = $individualUser->individual;
    $individual->individualRoles()->sync([$individualParticipantRole->id]);

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertDontSee('Projects I am contracted for');
    $response->assertSee('Projects I am participating in');

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-running-projects'));
    $response->assertNotFound();

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-contracted-projects'));
    $response->assertNotFound();

    $individual->individualRoles()->sync([$individualParticipantRole->id, $individualConsultantRole->id]);

    $individualUser = $individualUser->fresh();

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertSee('Projects I am contracted for');

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-contracted-projects'));
    $response->assertOk();

    $individual->individualRoles()->sync([$individualConsultantRole->id]);

    $individualUser = $individualUser->fresh();

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertDontSee('Projects I am participating in');
    $response->assertSee('Projects I am contracted for');

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])
        ->create();
    $regulatedOrganizationUser = $regulatedOrganizationUser->fresh();

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertSee('Projects I am running');
    $response->assertDontSee('Projects I am contracted for');
    $response->assertDontSee('Projects I am participating in');

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-contracted-projects'));
    $response->assertNotFound();

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-participating-projects'));
    $response->assertNotFound();

    $organizationConsultantRole = OrganizationRole::where('name->en', 'Accessibility Consultant')->first();
    $organizationParticipantRole = OrganizationRole::where('name->en', 'Consultation Participant')->first();

    $organizationUser = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => 'admin'])
        ->create();
    $organizationUser = $organizationUser->fresh();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertSee('Projects I am running');
    $response->assertDontSee('Projects I am contracted for');
    $response->assertDontSee('Projects I am participating in');

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-contracted-projects'));
    $response->assertNotFound();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-participating-projects'));
    $response->assertNotFound();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-running-projects'));
    $response->assertNotFound();

    $organization->organizationRoles()->sync([$organizationConsultantRole->id]);
    $organizationUser = $organizationUser->fresh();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertSee('Projects I am running');
    $response->assertSee('Projects I am contracted for');
    $response->assertDontSee('Projects I am participating in');

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-participating-projects'));
    $response->assertNotFound();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-contracted-projects'));
    $response->assertNotFound();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-running-projects'));
    $response->assertOk();

    $organization->organizationRoles()->sync([$organizationConsultantRole->id, $organizationParticipantRole->id]);
    $organizationUser = $organizationUser->fresh();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertSee('Projects I am running');
    $response->assertSee('Projects I am contracted for');
    $response->assertSee('Projects I am participating in');

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-participating-projects'));
    $response->assertOk();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-contracted-projects'));
    $response->assertNotFound();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-running-projects'));
    $response->assertOk();

    $organization->organizationRoles()->sync([$organizationParticipantRole->id]);
    $organizationUser = $organizationUser->fresh();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertSee('Projects I am running');
    $response->assertDontSee('Projects I am contracted for');
    $response->assertSee('<h2>Projects I am participating in</h2>', false);

    $traineeUser = User::factory()->create(['context' => 'regulated-organization-employee']);
    $response = $this->actingAs($traineeUser)->get(localized_route('projects.my-projects'));
    $response->assertNotFound();
});

test('guests can not access my projects page', function () {
    $response = $this->get(localized_route('projects.my-projects'));
    $response->assertRedirect(localized_route('login'));
});
