<?php

use App\Models\Criterion;
use App\Models\DisabilityType;
use App\Models\Engagement;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\MatchingStrategy;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Sector;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\DisabilityTypeSeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use function Pest\Faker\faker;

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
    $this->seed(ImpactSeeder::class);
    $adminUser = User::factory()->create(['context' => 'regulated-organization']);
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($adminUser, ['role' => 'admin'])
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
        'contact_person_phone' => '4165555555',
        'contact_person_response_time' => ['en' => '48 hours'],
        'preferred_contact_method' => 'required',
        'team_languages' => ['en'],
        'team_trainings' => [
            [
                'date' => date('Y-m-d', time()),
                'name' => 'test training',
                'trainer_name' => 'trainer',
                'trainer_url' => 'http://example.com',
            ],
        ],
    ]);

    $project->impacts()->attach(Impact::first()->id);

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
    $response->assertSee('Draft');
});

test('project isPublishable()', function ($expected, $data, $connections = [], $context = 'organization') {
    $this->seed(ImpactSeeder::class);

    $adminUser = User::factory()->create(['context' => $context]);
    $user = User::factory()->create(['context' => $context]);
    $orgModel = $context === 'organization' ? Organization::class : RegulatedOrganization::class;
    $organization = $orgModel::factory()
        ->hasAttached($adminUser, ['role' => 'admin'])
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    // fill data so that we don't hit a Database Integrity constraint violation during creation
    $project = Project::factory()->create([
        'projectable_id' => $organization->id,
    ]);
    $project->fill($data);

    foreach ($connections as $connection) {
        if ($connection === 'impacts') {
            $project->impacts()->attach(Impact::first()->id);
        }
    }

    expect($project->isPublishable())->toBe($expected);
})->with('projectIsPublishable');

test('users can view projects', function () {
    $user = User::factory()->create();
    $adminUser = User::factory()->create(['context' => 'regulated-organization', 'phone' => '19024444567']);
    $regulatedOrganization = RegulatedOrganization::factory()->create([
        'contact_person_name' => $adminUser->name,
        'contact_person_email' => $adminUser->email,
        'contact_person_phone' => $adminUser->phone,
        'preferred_contact_method' => $adminUser->preferred_contact_method,
    ]);
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
        'contact_person_name' => $regulatedOrganization->contact_person_name,
        'contact_person_email' => $regulatedOrganization->contact_person_email,
        'contact_person_phone' => $regulatedOrganization->contact_person_phone,
        'preferred_contact_method' => $regulatedOrganization->preferred_contact_method,
    ]);

    $response = $this->actingAs($user)->get(localized_route('projects.all-projects'));
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

test('notifications can be routed for projects', function () {
    $project = Project::factory()->create([
        'contact_person_name' => faker()->name(),
        'contact_person_email' => faker()->email(),
        'contact_person_phone' => '19024445678',
        'preferred_contact_method' => 'email',
    ]);

    expect($project->routeNotificationForVonage(new \Illuminate\Notifications\Notification()))->toEqual($project->contact_person_phone);
    expect($project->routeNotificationForMail(new \Illuminate\Notifications\Notification()))->toEqual([$project->contact_person_email => $project->contact_person_name]);
});

test('guests cannot view projects', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $response = $this->get(localized_route('projects.all-projects'));
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
        'outcome_analysis' => $project->outcome_analysis,
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
        'outcome_analysis' => $project->outcome_analysis,
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 2]));

    $response = $this->actingAs($user)->put(localized_route('projects.update-team', $project), [
        'team_count' => '42',
        'team_languages' => ['en'],
        'team_trainings' => [
            ['name' => 'Example Training', 'date' => '2022-04-01', 'trainer_name' => 'Acme Training Co.', 'trainer_url' => 'example.com'],
        ],
        'contact_person_email' => 'me@here.com',
        'contact_person_name' => 'Jonny Appleseed',
        'preferred_contact_method' => 'email',
        'contact_person_response_time' => ['en' => 'ASAP'],
        'save_and_previous' => __('Save and previous'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));

    $project = $project->fresh();
    expect($project->team_trainings[0]['trainer_url'])->toEqual('https://example.com');
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

    $response = $this->actingAs($user)->get(localized_route('projects.manage-estimates-and-agreements', $project));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('projects.suggested-steps', $project));
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
        'end_date' => null,
    ]);

    expect($org_past_project)->finished->toBeTrue();
    expect($org_past_project->status)->toEqual('Complete');
    expect($org_current_project->finished)->toBeFalse();
    expect($org_current_project->status)->toEqual('In progress');
    expect($indeterminate_project->finished)->toBeFalse();
    expect($org_current_project->started)->toBeTrue();
    expect($org_future_project->started)->toBeFalse();
    expect($org_future_project->status)->toEqual('Upcoming');
    expect($indeterminate_project)->started->toBeFalse();

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

    $this->assertEquals('Our team has people with lived and living experiences of disability or being Deaf.', $project->teamExperience());

    $project->update([
        'team_has_disability_or_deaf_lived_experience' => false,
    ]);

    $project = $project->fresh();

    $this->assertEquals('Our team does not have people with lived and living experiences of disability or being Deaf.', $project->teamExperience());
});

test('project retrieves team trainings properly', function () {
    $project = Project::factory()->create([
        'team_trainings' => [
            ['name' => 'Example Training', 'date' => '2022-04-01', 'trainer_name' => 'Acme Training Co.', 'trainer_url' => 'https://acme.training'],
        ],
    ]);

    expect($project->team_trainings[0]['date'])->toEqual('2022-04-01');

    $projectWithNullTrainings = Project::factory()->create([
        'team_trainings' => [
            ['name' => '', 'date' => '', 'trainer_name' => '', 'trainer_url' => ''],
        ],
    ]);

    expect($projectWithNullTrainings->team_trainings)->toBeEmpty();
});

test('registered users can access my projects page', function () {
    $individualUser = User::factory()->create();
    $individual = $individualUser->individual;
    $individual->roles = ['participant'];
    $individual->save();
    $individual = $individual->fresh();

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertDontSee('Projects I am contracted for');
    $response->assertSee('Projects I am participating in');

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-running-projects'));
    $response->assertNotFound();

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-contracted-projects'));
    $response->assertNotFound();

    $individual->roles = ['participant', 'consultant'];
    $individual->save();
    $individualUser = $individualUser->fresh();

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertSee('Projects I am contracted for');

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-contracted-projects'));
    $response->assertOk();

    $individual->roles = ['consultant'];
    $individual->save();
    $individualUser = $individualUser->fresh();

    $response = $this->actingAs($individualUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertDontSee('Projects I am participating in');
    $response->assertSee('Projects I am contracted for');

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    RegulatedOrganization::factory()
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

    $organization->roles = ['consultant'];
    $organization->save();
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

    $organization->roles = ['consultant', 'participant'];
    $organization->save();
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

    $organization->roles = ['participant'];
    $organization->save();
    $organizationUser = $organizationUser->fresh();

    $response = $this->actingAs($organizationUser)->get(localized_route('projects.my-projects'));
    $response->assertOk();
    $response->assertSee('Projects I am running');
    $response->assertDontSee('Projects I am contracted for');
    $response->assertSee('<h2>Projects I am participating in</h2>', false);

    $traineeUser = User::factory()->create(['context' => 'regulated-organization-employee']);
    $response = $this->actingAs($traineeUser)->get(localized_route('projects.my-projects'));
    $response->assertForbidden();
});

test('guests can not access my projects page', function () {
    $response = $this->get(localized_route('projects.my-projects'));
    $response->assertRedirect(localized_route('login'));
});

test('test project statuses scope', function () {
    $upcomingProject = Project::factory()->create([
        'start_date' => Carbon::now()->addDays(5),
    ]);

    $inProgressProject = Project::factory()->create([
        'start_date' => Carbon::now()->subDays(5),
        'end_date' => Carbon::now()->addDays(5),
    ]);

    $completedProject = Project::factory()->create([
        'end_date' => Carbon::now()->subDays(5),
    ]);

    $statusQuery = Project::statuses(['upcoming'])->get();

    expect($statusQuery->contains($upcomingProject))->toBeTrue();
    expect($statusQuery->contains($inProgressProject))->toBeFalse();
    expect($statusQuery->contains($completedProject))->toBeFalse();

    $statusQuery = Project::statuses(['inProgress'])->get();

    expect($statusQuery->contains($inProgressProject))->toBeTrue();
    expect($statusQuery->contains($upcomingProject))->toBeFalse();
    expect($statusQuery->contains($completedProject))->toBeFalse();

    $statusQuery = Project::statuses(['completed'])->get();

    expect($statusQuery->contains($completedProject))->toBeTrue();
    expect($statusQuery->contains($inProgressProject))->toBeFalse();
    expect($statusQuery->contains($upcomingProject))->toBeFalse();

    $statusQuery = Project::statuses(['upcoming', 'inProgress', 'completed'])->get();

    expect($statusQuery->contains($upcomingProject))->toBeTrue();
    expect($statusQuery->contains($inProgressProject))->toBeTrue();
    expect($statusQuery->contains($completedProject))->toBeTrue();
});

test('test project seekings scope', function () {
    $projectSeekingParticipants = Project::factory()->create();

    $openCallEngagement = Engagement::factory()->create(['recruitment' => 'open-call', 'project_id' => $projectSeekingParticipants->id]);

    $projectSeekingConnectors = Project::factory()->create();

    $connectorEngagement = Engagement::factory()->create(['recruitment' => 'connector', 'project_id' => $projectSeekingConnectors->id, 'extra_attributes' => ['seeking_community_connector' => true]]);

    $projectSeekingOrganizations = Project::factory()->create();

    $organizationEngagement = Engagement::factory()->create(['recruitment' => 'connector', 'who' => 'organization', 'project_id' => $projectSeekingOrganizations->id]);

    $seekingQuery = Project::seekings(['participants'])->get();

    expect($seekingQuery->contains($projectSeekingParticipants))->toBeTrue();
    expect($seekingQuery->contains($projectSeekingConnectors))->toBeFalse();
    expect($seekingQuery->contains($projectSeekingOrganizations))->toBeFalse();

    $seekingQuery = Project::seekings(['connectors'])->get();

    expect($seekingQuery->contains($projectSeekingConnectors))->toBeTrue();
    expect($seekingQuery->contains($projectSeekingParticipants))->toBeFalse();
    expect($seekingQuery->contains($projectSeekingOrganizations))->toBeFalse();

    $seekingQuery = Project::seekings(['organizations'])->get();

    expect($seekingQuery->contains($projectSeekingOrganizations))->toBeTrue();
    expect($seekingQuery->contains($projectSeekingConnectors))->toBeFalse();
    expect($seekingQuery->contains($projectSeekingParticipants))->toBeFalse();

    $seekingQuery = Project::seekings(['participants', 'connectors', 'organizations'])->get();

    expect($seekingQuery->contains($projectSeekingParticipants))->toBeTrue();
    expect($seekingQuery->contains($projectSeekingConnectors))->toBeTrue();
    expect($seekingQuery->contains($projectSeekingOrganizations))->toBeTrue();
});

test('test project initiators scope', function () {
    $communityOrganizationProject = Project::factory()
        ->create(['projectable_type' => 'App\Models\Organization']);

    $regulatedOrganizationProject = Project::factory()
        ->create(['projectable_type' => 'App\Models\RegulatedOrganization']);

    $initiatorQuery = Project::initiators(['organization'])->get();

    expect($initiatorQuery->contains($communityOrganizationProject))->toBeTrue();
    expect($initiatorQuery->contains($regulatedOrganizationProject))->toBeFalse();

    $initiatorQuery = Project::initiators(['regulatedOrganization'])->get();

    expect($initiatorQuery->contains($regulatedOrganizationProject))->toBeTrue();
    expect($initiatorQuery->contains($communityOrganizationProject))->toBeFalse();

    $initiatorQuery = Project::initiators(['regulatedOrganization', 'organization'])->get();

    expect($initiatorQuery->contains($regulatedOrganizationProject))->toBeTrue();
    expect($initiatorQuery->contains($communityOrganizationProject))->toBeTrue();
});

test('test project seekingGroups scope', function () {
    $this->seed(DisabilityTypeSeeder::class);

    $disabilityTypeDeaf = DisabilityType::where('name->en', 'Deaf')->first();
    $projectSeekingDeafExperience = Project::factory()->create();
    $engagementSeekingDeafExperience = Engagement::factory()->create(['project_id' => $projectSeekingDeafExperience->id]);
    $matchingStrategySeekingDeafExperience = MatchingStrategy::factory()->create([
        'matchable_type' => 'App\Models\Engagement',
        'matchable_id' => $engagementSeekingDeafExperience->id,
    ]);
    $deafCriterion = Criterion::factory()->create([
        'matching_strategy_id' => $matchingStrategySeekingDeafExperience->id,
        'criteriable_type' => 'App\Models\DisabilityType',
        'criteriable_id' => $disabilityTypeDeaf->id,
    ]);

    $disabilityTypeCognitive = DisabilityType::where('name->en', 'Cognitive disabilities')->first();
    $projectSeekingCognitiveDisabilityExperience = Project::factory()->create();
    $engagementSeekingCognitiveDisabilityExperience = Engagement::factory()->create(['project_id' => $projectSeekingCognitiveDisabilityExperience->id]);
    $matchingStrategySeekingCognitiveDisabilityExperience = MatchingStrategy::factory()->create([
        'matchable_type' => 'App\Models\Engagement',
        'matchable_id' => $engagementSeekingCognitiveDisabilityExperience->id,
    ]);
    $cognitiveDisabilityCriterion = Criterion::factory()->create([
        'matching_strategy_id' => $matchingStrategySeekingCognitiveDisabilityExperience->id,
        'criteriable_type' => 'App\Models\DisabilityType',
        'criteriable_id' => $disabilityTypeCognitive->id,
    ]);

    $seekingGroupQuery = Project::seekingGroups([$disabilityTypeDeaf->id])->get();

    expect($seekingGroupQuery->contains($projectSeekingDeafExperience))->toBeTrue();
    expect($seekingGroupQuery->contains($projectSeekingCognitiveDisabilityExperience))->toBeFalse();

    $seekingGroupQuery = Project::seekingGroups([$disabilityTypeCognitive->id])->get();
    expect($seekingGroupQuery->contains($projectSeekingCognitiveDisabilityExperience))->toBeTrue();
    expect($seekingGroupQuery->contains($projectSeekingDeafExperience))->toBeFalse();
});

test('test project meetingTypes scope', function () {
    $inpersonInterviewProject = Project::factory()->create();
    $inPersonInterviewEngagement = Engagement::factory()->create(['project_id' => $inpersonInterviewProject->id, 'extra_attributes' => ['format' => 'interviews'], 'meeting_types' => 'in_person']);

    $virtualWorkshopProject = Project::factory()->create();
    $virtualWorkshopEngagement = Engagement::factory()->create(['project_id' => $virtualWorkshopProject->id, 'extra_attributes' => ['format' => 'workshop'], 'meeting_types' => null]);
    $virtualWorkshopMeeting = Meeting::factory()->create(['engagement_id' => $virtualWorkshopEngagement->id, 'meeting_types' => 'web_conference']);

    $phoneFocusGroupProject = Project::factory()->create();
    $phoneFocusGroupEngagement = Engagement::factory()->create(['project_id' => $phoneFocusGroupProject->id, 'extra_attributes' => ['format' => 'focus-group'], 'meeting_types' => null]);
    $phoneFocusGroupMeeting = Meeting::factory()->create(['engagement_id' => $phoneFocusGroupEngagement->id, 'meeting_types' => 'phone']);

    $meetingTypeQuery = Project::meetingTypes(['in_person'])->get();

    expect($meetingTypeQuery->contains($inpersonInterviewProject))->toBeTrue();
    expect($meetingTypeQuery->contains($virtualWorkshopProject))->toBeFalse();
    expect($meetingTypeQuery->contains($phoneFocusGroupProject))->toBeFalse();

    $meetingTypeQuery = Project::meetingTypes(['web_conference'])->get();

    expect($meetingTypeQuery->contains($virtualWorkshopProject))->toBeTrue();
    expect($meetingTypeQuery->contains($inpersonInterviewProject))->toBeFalse();
    expect($meetingTypeQuery->contains($phoneFocusGroupProject))->toBeFalse();

    $meetingTypeQuery = Project::meetingTypes(['phone'])->get();

    expect($meetingTypeQuery->contains($phoneFocusGroupProject))->toBeTrue();
    expect($meetingTypeQuery->contains($virtualWorkshopProject))->toBeFalse();
    expect($meetingTypeQuery->contains($inpersonInterviewProject))->toBeFalse();

    $meetingTypeQuery = Project::meetingTypes(['in_person', 'web_conference', 'phone'])->get();

    expect($meetingTypeQuery->contains($inpersonInterviewProject))->toBeTrue();
    expect($meetingTypeQuery->contains($virtualWorkshopProject))->toBeTrue();
    expect($meetingTypeQuery->contains($phoneFocusGroupProject))->toBeTrue();
});

test('test project compensations scope', function () {
    $paidProject = Project::factory()->create();
    $paidEngagement = Engagement::factory()->create(['project_id' => $paidProject->id, 'paid' => true]);

    $volunteerProject = Project::factory()->create();
    $volunteerEngagement = Engagement::factory()->create(['project_id' => $volunteerProject->id, 'paid' => false]);

    $compensationQuery = Project::compensations(['paid'])->get();

    expect($compensationQuery->contains($paidProject))->toBeTrue();
    expect($compensationQuery->contains($volunteerProject))->toBeFalse();

    $compensationQuery = Project::compensations(['volunteer'])->get();

    expect($compensationQuery->contains($volunteerProject))->toBeTrue();
    expect($compensationQuery->contains($paidProject))->toBeFalse();

    $compensationQuery = Project::compensations(['volunteer', 'paid'])->get();

    expect($compensationQuery->contains('id', $volunteerProject->id))->toBeTrue();
    expect($compensationQuery->contains('id', $paidProject->id))->toBeTrue();
});

test('test project sectors scope', function () {
    $this->seed(SectorSeeder::class);
    $transportationSector = Sector::where('name->en', 'Transportation')->first();
    $transportationRegulatedOrganization = RegulatedOrganization::factory()->create();
    $transportationRegulatedOrganization->sectors()->save($transportationSector);
    $transportationProject = Project::factory()->create(['projectable_id' => $transportationRegulatedOrganization->id]);

    $telecommunicationSector = Sector::where('name->en', 'Telecommunications')->first();
    $telecommunicationRegulatedOrganization = RegulatedOrganization::factory()->create();
    $telecommunicationRegulatedOrganization->sectors()->save($telecommunicationSector);
    $telecommunicationProject = Project::factory()->create(['projectable_id' => $telecommunicationRegulatedOrganization->id]);

    $sectorQuery = Project::sectors([$transportationSector->id])->get();

    expect($sectorQuery->contains($transportationProject))->toBeTrue();
    expect($sectorQuery->contains($telecommunicationProject))->toBeFalse();

    $sectorQuery = Project::sectors([$telecommunicationSector->id])->get();

    expect($sectorQuery->contains($telecommunicationProject))->toBeTrue();
    expect($sectorQuery->contains($transportationProject))->toBeFalse();

    $sectorQuery = Project::sectors([$transportationSector->id, $telecommunicationSector->id])->get();

    expect($sectorQuery->contains($transportationProject))->toBeTrue();
    expect($sectorQuery->contains($telecommunicationProject))->toBeTrue();
});

test('test project areas of impact scope', function () {
    $this->seed(ImpactSeeder::class);
    $employmentImpact = Impact::where('name->en', 'Employment')->first();
    $employmentImpactProject = Project::factory()->create();
    $employmentImpactProject->impacts()->attach($employmentImpact->id);

    $communicationImpact = Impact::where('name->en', 'Communication, other than information and communication technologies')->first();
    $communicationImpactProject = Project::factory()->create();
    $communicationImpactProject->impacts()->attach($communicationImpact->id);

    $impactQuery = Project::areasOfImpact([$employmentImpact->id])->get();

    expect($impactQuery->contains($employmentImpactProject))->toBeTrue();
    expect($impactQuery->contains($communicationImpactProject))->toBeFalse();

    $impactQuery = Project::areasOfImpact([$communicationImpact->id])->get();

    expect($impactQuery->contains($communicationImpactProject))->toBeTrue();
    expect($impactQuery->contains($employmentImpactProject))->toBeFalse();

    $impactQuery = Project::areasOfImpact([$employmentImpact->id, $communicationImpact->id])->get();

    expect($impactQuery->contains($employmentImpactProject))->toBeTrue();
    expect($impactQuery->contains($communicationImpactProject))->toBeTrue();
});

test('test project recruitment methods scope', function () {
    $openCallProject = Project::factory()->create();
    $openCallEngagement = Engagement::factory()->create(['project_id' => $openCallProject->id, 'recruitment' => 'open-call']);

    $connectorProject = Project::factory()->create();
    $connectorEngagement = Engagement::factory()->create(['project_id' => $connectorProject->id, 'recruitment' => 'connector']);

    $recruitmentMethodQuery = Project::recruitmentMethods(['open-call'])->get();

    expect($recruitmentMethodQuery->contains($openCallProject))->toBeTrue();
    expect($recruitmentMethodQuery->contains($connectorProject))->toBeFalse();

    $recruitmentMethodQuery = Project::recruitmentMethods(['connector'])->get();

    expect($recruitmentMethodQuery->contains($connectorProject))->toBeTrue();
    expect($recruitmentMethodQuery->contains($openCallProject))->toBeFalse();

    $recruitmentMethodQuery = Project::recruitmentMethods(['connector', 'open-call'])->get();

    expect($recruitmentMethodQuery->contains($connectorProject))->toBeTrue();
    expect($recruitmentMethodQuery->contains($openCallProject))->toBeTrue();
});

test('test locations scope', function () {
    $regionSpecificProject = Project::factory()->create();
    $regionSpecificEngagement = Engagement::factory()->create(['project_id' => $regionSpecificProject->id]);
    $regionSpecificMatchingStrategy = $regionSpecificEngagement->matchingStrategy;
    $regionSpecificMatchingStrategy->update([
        'regions' => ['AB'],
    ]);

    $locationSpecificProject = Project::factory()->create();
    $locationSpecificEngagement = Engagement::factory()->create(['project_id' => $locationSpecificProject->id]);
    $locationSpecificMatchingStrategy = $locationSpecificEngagement->matchingStrategy;

    $locationSpecificMatchingStrategy->update([
        'locations' => [
            ['region' => 'AB', 'locality' => 'Edmonton'],
            ['region' => 'ON', 'locality' => 'Toronto'],
        ],
    ]);

    $locationQuery = Project::locations(['AB'])->get();

    expect($locationQuery->contains($regionSpecificProject))->toBeTrue();
    expect($locationQuery->contains($locationSpecificProject))->toBeTrue();

    $locationQuery = Project::locations(['ON'])->get();

    expect($locationQuery->contains($regionSpecificProject))->toBeFalse();
    expect($locationQuery->contains($locationSpecificProject))->toBeTrue();

    $locationQuery = Project::locations(['AB', 'ON'])->get();

    expect($locationQuery->contains($regionSpecificProject))->toBeTrue();
    expect($locationQuery->contains($locationSpecificProject))->toBeTrue();
});
