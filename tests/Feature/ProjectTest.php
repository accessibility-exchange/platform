<?php

use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Models\Engagement;
use App\Models\Identity;
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
use Database\Seeders\IdentitySeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;

test('users with organization or regulated organization admin role can create projects', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();

    expect($user->projects())->toHaveCount(0);

    actingAs($user)->get(localized_route('projects.show-context-selection'))->assertOk();

    actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'new',
    ])
        ->assertSessionMissing('ancestor');

    actingAs($user)->get(localized_route('projects.create'))->assertOk();

    actingAs($user)->post(localized_route('projects.store-languages'), [
        'languages' => config('locales.supported'),
    ])->assertSessionHas('languages', config('locales.supported'));

    $response = actingAs($user)->post(localized_route('projects.store'), [
        'projectable_id' => $regulatedOrganization->id,
        'projectable_type' => 'App\Models\RegulatedOrganization',
        'name' => ['en' => 'Test Project'],
    ]);

    $project = Project::where('name->en', 'Test Project')->first();
    $url = localized_route('projects.edit', ['project' => $project, 'step' => 1]);

    $response->assertSessionHasNoErrors();

    expect($project->name)->toEqual('Test Project');

    $response->assertRedirect($url);

    $user = $user->fresh();

    expect($user->projects())->toHaveCount(1);

    $previous_project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'follow-up',
        'ancestor' => $previous_project->id,
    ])
        ->assertSessionHas('ancestor', $previous_project->id);

    $user = User::factory()->create(['context' => UserContext::Organization->value]);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();

    actingAs($user)->get(localized_route('projects.create'))->assertOk();

    actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'new',
    ])
        ->assertSessionMissing('ancestor');

    actingAs($user)->get(localized_route('projects.show-language-selection'))->assertOk();

    actingAs($user)->post(localized_route('projects.store-languages'), [
        'languages' => config('locales.supported'),
    ])
        ->assertSessionHas('languages', config('locales.supported'));

    $response = actingAs($user)->post(localized_route('projects.store'), [
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

    expect($project->name)->toEqual('Test Project 2');

    $response->assertRedirect($url);

    $user = $user->fresh();

    expect($user->projects())->toHaveCount(1);

    $previous_project = Project::factory()->create([
        'projectable_id' => $organization->id,
    ]);

    actingAs($user)->post(localized_route('projects.store-context'), [
        'context' => 'follow-up',
        'ancestor' => $previous_project->id,
    ])
        ->assertSessionHas('ancestor', $previous_project->id);
});

test('users without regulated organization admin role cannot create projects', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Member->value])
        ->create();

    actingAs($user)->get(localized_route('projects.create'))->assertForbidden();

    actingAs($other_user)->get(localized_route('projects.create'))->assertForbidden();
});

test('projects can be published and unpublished', function () {
    $this->seed(ImpactSeeder::class);
    $adminUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($adminUser, ['role' => TeamRole::Administrator->value])
        ->hasAttached($user, ['role' => TeamRole::Member->value])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
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
    ]);

    $project->impacts()->attach(Impact::first()->id);

    expect($project->isPublishable())->toBeTrue();

    actingAs($user)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'publish' => true,
    ])
        ->assertForbidden();

    actingAs($adminUser)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'publish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('projects.show', $project));
    expect($project->checkStatus('published'))->toBeTrue();

    actingAs($user)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'unpublish' => true,
    ])
        ->assertForbidden();

    actingAs($adminUser)->from(localized_route('projects.show', $project))->put(localized_route('projects.update-publication-status', $project), [
        'unpublish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('projects.show', $project));

    $project = $project->fresh();

    expect($project->checkStatus('draft'))->toBeTrue();

    actingAs($adminUser)->get(localized_route('projects.show', $project))->assertSee('Draft');
});

test('project isPublishable()', function ($expected, $data, $connections = [], $context = 'organization', $projectableData = []) {
    $this->seed(ImpactSeeder::class);

    $adminUser = User::factory()->create(['context' => $context]);
    $user = User::factory()->create(['context' => $context]);
    $orgModel = $context === 'organization' ? Organization::class : RegulatedOrganization::class;
    $organization = $orgModel::factory()
        ->hasAttached($adminUser, ['role' => TeamRole::Administrator->value])
        ->hasAttached($user, ['role' => TeamRole::Member->value])
        ->create();
    $organization->update($projectableData);
    $organization = $organization->fresh();

    // fill data so that we don't hit a Database Integrity constraint violation during creation
    $project = Project::factory()->create([
        'projectable_type' => $orgModel,
        'projectable_id' => $organization->id,
    ]);
    $project = $project->fresh();
    $project->fill($data);

    foreach ($connections as $connection) {
        if ($connection === 'impacts') {
            $project->impacts()->attach(Impact::first()->id);
        }
    }

    expect($project->isPublishable())->toBe($expected);
})->with('projectIsPublishable');

test('users can not view projects, other than their owned project, if they are not oriented', function () {
    $pendingUser = User::factory()->create(['oriented_at' => null]);

    actingAs($pendingUser)->get(localized_route('projects.my-projects'))->assertOk();

    actingAs($pendingUser)->get(localized_route('projects.my-contracted-projects'))->assertForbidden();

    actingAs($pendingUser)->get(localized_route('projects.my-participating-projects'))->assertForbidden();

    actingAs($pendingUser)->get(localized_route('projects.my-running-projects'))->assertOk();

    actingAs($pendingUser)->get(localized_route('projects.all-projects'))->assertForbidden();

    $pendingUser->update(['oriented_at' => now()]);

    actingAs($pendingUser)->get(localized_route('projects.my-projects'))->assertOk();

    actingAs($pendingUser)->get(localized_route('projects.all-projects'))->assertOk();
});

test('organization or regulated organization users can not view projects, other than their owned project, if they are not oriented', function () {
    $organizationUser = User::factory()->create(['context' => 'organization', 'oriented_at' => null]);
    $organization = Organization::factory()->hasAttached($organizationUser, ['role' => 'admin'])->create(['oriented_at' => null]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('projects.my-projects'))->assertOk();

    actingAs($organizationUser)->get(localized_route('projects.my-contracted-projects'))->assertForbidden();

    actingAs($organizationUser)->get(localized_route('projects.my-participating-projects'))->assertForbidden();

    actingAs($organizationUser)->get(localized_route('projects.my-running-projects'))->assertOk();

    actingAs($organizationUser)->get(localized_route('projects.all-projects'))->assertForbidden();

    $organization->update(['oriented_at' => now()]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('projects.my-projects'))->assertOk();

    actingAs($organizationUser)->get(localized_route('projects.all-projects'))->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization', 'oriented_at' => null]);
    $regulatedOrganization = RegulatedOrganization::factory()->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])->create(['oriented_at' => null]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-projects'))->assertForbidden();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-contracted-projects'))->assertForbidden();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-participating-projects'))->assertForbidden();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-running-projects'))->assertForbidden();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.all-projects'))->assertForbidden();

    $regulatedOrganization->update(['oriented_at' => now()]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-projects'))->assertOk();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.all-projects'))->assertOk();
});

test('users can view projects', function () {
    $user = User::factory()->create();
    $adminUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value, 'phone' => '19024444567']);
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

    actingAs($user)->get(localized_route('projects.all-projects'))->assertOk();

    actingAs($user)->get(localized_route('projects.show', $project))->assertOk();

    actingAs($user)->get(localized_route('projects.show-team', $project))->assertOk();

    actingAs($user)->get(localized_route('projects.show-engagements', $project))->assertOk();

    actingAs($user)->get(localized_route('projects.show-outcomes', $project))->assertOk();
});

test('users can view project engagements', function () {
    $user = User::factory()->create();
    $adminUser = User::factory()->create(['context' => UserContext::Administrator->value]);
    $orgAdminUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value, 'phone' => '19024444567']);
    $orgMemberUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($orgAdminUser, ['role' => TeamRole::Administrator->value])
        ->hasAttached($orgMemberUser, ['role' => TeamRole::Member->value])
        ->create([
            'contact_person_name' => $orgAdminUser->name,
            'contact_person_email' => $orgAdminUser->email,
            'contact_person_phone' => $orgAdminUser->phone,
            'preferred_contact_method' => $orgAdminUser->preferred_contact_method,
        ]);

    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
        'contact_person_name' => $regulatedOrganization->contact_person_name,
        'contact_person_email' => $regulatedOrganization->contact_person_email,
        'contact_person_phone' => $regulatedOrganization->contact_person_phone,
        'preferred_contact_method' => $regulatedOrganization->preferred_contact_method,
    ]);

    $publishedEngagement = Engagement::factory()
        ->for($project)
        ->has(Meeting::factory())
        ->create([
            'name' => ['en' => 'Published'],
            'description' => ['en' => 'Published engagment'],
            'signup_by_date' => now(),
        ]);

    $previewableEngagement = Engagement::factory()
        ->for($project)
        ->has(Meeting::factory())
        ->create([
            'name' => ['en' => 'Previewable'],
            'description' => ['en' => 'Previewable engagment'],
            'signup_by_date' => now(),
            'published_at' => null,
        ]);

    $unpreviewableEngagement = Engagement::factory()
        ->for($project)
        ->create([
            'name' => ['en' => 'Previewable'],
            'description' => ['en' => 'Previewable engagment'],
            'published_at' => null,
        ]);

    // Org admin
    $response = actingAs($orgAdminUser)->get(localized_route('projects.show', $project))->assertOk();
    expect($response['engagements'])->toHaveCount(3);
    expect($response['engagements']->modelKeys())->toContain($publishedEngagement->id, $previewableEngagement->id, $unpreviewableEngagement->id);

    $response = actingAs($orgAdminUser)->get(localized_route('projects.show-engagements', $project))->assertOk();
    expect($response['engagements'])->toHaveCount(3);
    expect($response['engagements']->modelKeys())->toContain($publishedEngagement->id, $previewableEngagement->id, $unpreviewableEngagement->id);

    // Org member
    $response = actingAs($orgMemberUser)->get(localized_route('projects.show', $project))->assertOk();
    expect($response['engagements'])->toHaveCount(1);
    expect($response['engagements']->modelKeys())->toContain($publishedEngagement->id);

    $response = actingAs($orgMemberUser)->get(localized_route('projects.show-engagements', $project))->assertOk();
    expect($response['engagements'])->toHaveCount(1);
    expect($response['engagements']->modelKeys())->toContain($publishedEngagement->id);

    // Site admin
    $response = actingAs($adminUser)->get(localized_route('projects.show', $project))->assertOk();
    expect($response['engagements'])->toHaveCount(2);
    expect($response['engagements']->modelKeys())->toContain($publishedEngagement->id, $previewableEngagement->id);

    $response = actingAs($adminUser)->get(localized_route('projects.show-engagements', $project))->assertOk();
    expect($response['engagements'])->toHaveCount(2);
    expect($response['engagements']->modelKeys())->toContain($publishedEngagement->id, $previewableEngagement->id);

    // Site user
    $response = actingAs($user)->get(localized_route('projects.show', $project))->assertOk();
    expect($response['engagements'])->toHaveCount(1);
    expect($response['engagements']->modelKeys())->toContain($publishedEngagement->id);

    $response = actingAs($user)->get(localized_route('projects.show-engagements', $project))->assertOk();
    expect($response['engagements'])->toHaveCount(1);
    expect($response['engagements']->modelKeys())->toContain($publishedEngagement->id);
});

test('incomplete users cannot view projects page', function ($context, $redirectRoute) {
    $user = User::factory()->create(['context' => $context]);

    actingAs($user)->get(localized_route('projects.my-projects'))->assertRedirect(localized_route($redirectRoute));

    actingAs($user)->get(localized_route('projects.my-contracted-projects'))->assertNotFound();

    actingAs($user)->get(localized_route('projects.my-participating-projects'))->assertNotFound();

    actingAs($user)->get(localized_route('projects.my-running-projects'))->assertNotFound();
})->with([
    'organization context' => [
        'organization',
        'organizations.show-type-selection',
    ],
    'regulated-organization context' => [
        'regulated-organization',
        'regulated-organizations.show-type-selection',
    ],
]);

test('notifications can be routed for projects', function () {
    $project = Project::factory()->create([
        'contact_person_name' => fake()->name(),
        'contact_person_email' => fake()->email(),
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

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->get(localized_route('projects.edit', $project))->assertOk();

    actingAs($user)->put(localized_route('projects.update', $project), [
        'name' => ['en' => $project->name],
        'goals' => ['en' => 'Some new goals'],
        'scope' => ['en' => $project->scope],
        'regions' => ['AB', 'BC'],
        'impacts' => [Impact::first()->id],
        'start_date' => $project->start_date,
        'end_date' => $project->end_date,
        'outcome_analysis' => $project->outcome_analysis,
        'outcomes' => ['en' => 'Something.'],
        'public_outcomes' => true,
        'save' => __('Save'),
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));

    $project = $project->fresh();
    expect(1)->toEqual(count($project->impacts));

    actingAs($user)->put(localized_route('projects.update', $project), [
        'name' => ['en' => $project->name],
        'goals' => ['en' => 'Some newer goals'],
        'scope' => ['en' => $project->scope],
        'regions' => ['AB', 'BC'],
        'impacts' => [Impact::first()->id],
        'start_date' => $project->start_date,
        'end_date' => $project->end_date,
        'outcome_analysis' => $project->outcome_analysis,
        'outcomes' => ['en' => 'Something.'],
        'public_outcomes' => true,
        'save_and_next' => __('Save and next'),
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 2]));

    actingAs($user)->put(localized_route('projects.update-team', $project), [
        'team_count' => '42',
        'team_trainings' => [
            ['name' => 'Example Training', 'date' => '2022-04-01', 'trainer_name' => 'Acme Training Co.', 'trainer_url' => 'example.com'],
        ],
        'contact_person_email' => 'me@here.com',
        'contact_person_name' => 'Jonny Appleseed',
        'contact_person_vrs' => true,
        'preferred_contact_method' => 'email',
        'contact_person_response_time' => ['en' => 'ASAP'],
        'save_and_previous' => __('Save and previous'),
    ])
        ->assertSessionHasErrors(['contact_person_phone' => 'Since you have indicated that your contact person needs VRS, please enter a phone number.']);

    actingAs($user)->put(localized_route('projects.update-team', $project), [
        'team_count' => '42',
        'team_trainings' => [
            ['name' => 'Example Training', 'date' => '2022-04-01', 'trainer_name' => 'Acme Training Co.', 'trainer_url' => 'example.com'],
        ],
        'contact_person_email' => 'me@here.com',
        'contact_person_name' => 'Jonny Appleseed',
        'preferred_contact_method' => 'email',
        'contact_person_response_time' => ['en' => 'ASAP'],
        'save_and_previous' => __('Save and previous'),
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));

    $project = $project->fresh();
    expect($project->team_trainings[0]['trainer_url'])->toEqual('https://example.com');

    actingAs($user)->put(localized_route('projects.update-team', $project), [
        'team_count' => '42',
        'contact_person_email' => 'me@here.com',
        'contact_person_name' => 'Jonny Appleseed',
        'contact_person_phone' => '19024445678',
        'contact_person_vrs' => true,
        'preferred_contact_method' => 'email',
        'contact_person_response_time' => ['en' => 'ASAP'],
        'save_and_previous' => __('Save and previous'),
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));

    $project = $project->fresh();
    expect($project->contact_person_vrs)->toBeTrue();

    actingAs($user)->put(localized_route('projects.update-team', $project), [
        'team_count' => '42',
        'contact_person_email' => 'me@here.com',
        'contact_person_name' => 'Jonny Appleseed',
        'contact_person_phone' => '19024445678',
        'preferred_contact_method' => 'email',
        'contact_person_response_time' => ['en' => 'ASAP'],
        'save_and_previous' => __('Save and previous'),
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('projects.edit', ['project' => $project, 'step' => 1]));

    $project = $project->fresh();
    expect($project->contact_person_vrs)->toBeNull();
});

test('users without regulated organization admin role cannot edit projects', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Member->value])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->get(localized_route('projects.edit', $project))->assertForbidden();

    actingAs($user)->put(localized_route('projects.update', $project), [
        'name' => 'My updated project name',
    ])
        ->assertForbidden();

    actingAs($other_user)->get(localized_route('projects.edit', $project))->assertForbidden();

    actingAs($other_user)->put(localized_route('projects.update', $project), [
        'name' => 'My updated project name',
    ])
        ->assertForbidden();
});

test('users with regulated organization admin role can manage projects', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->get(localized_route('projects.manage', $project))->assertOk();

    actingAs($user)->get(localized_route('projects.manage-estimates-and-agreements', $project))->assertOk();

    actingAs($user)->get(localized_route('projects.suggested-steps', $project))->assertOk();
});

test('users without regulated organization admin role cannot manage projects', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Member->value])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->get(localized_route('projects.manage', $project))->assertForbidden();
});

test('users with regulated organization admin role can delete projects', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->get(localized_route('projects.edit', $project))->assertOk();

    actingAs($user)->from(localized_route('projects.edit', $project))->delete(localized_route('projects.destroy', $project), [
        'current_password' => 'password',
    ])
        ->assertRedirect(localized_route('dashboard'));
});

test('users without regulated organization admin role cannot delete projects', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $other_user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Member->value])
        ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('projects.destroy', $project), [
        'current_password' => 'password',
    ])
        ->assertForbidden();

    actingAs($other_user)->from(localized_route('dashboard'))->delete(localized_route('projects.destroy', $project), [
        'current_password' => 'password',
    ])
        ->assertForbidden();
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

    expect($organization->projects)->toHaveCount(5);
    expect($organization->completedProjects)->toHaveCount(2);
    expect($organization->inProgressProjects)->toHaveCount(1);
    expect($organization->upcomingProjects)->toHaveCount(1);

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

    expect($regulatedOrganization->projects)->toHaveCount(4);
    expect($regulatedOrganization->completedProjects)->toHaveCount(2);
    expect($regulatedOrganization->inProgressProjects)->toHaveCount(1);
    expect($regulatedOrganization->upcomingProjects)->toHaveCount(1);
});

test('projects reflect consultant origin', function () {
    $individual = Individual::factory()->create();

    $project_with_external_consultant = Project::factory()->create([
        'consultant_name' => 'Joe Consultant',
    ]);

    $project_with_platform_consultant = Project::factory()->create([
        'individual_consultant_id' => $individual->id,
    ]);

    expect($project_with_external_consultant->consultant_origin)->toEqual('external');
    expect($project_with_platform_consultant->consultant_origin)->toEqual('platform');
    expect($project_with_platform_consultant->consultant->id)->toEqual($individual->id);
});

test('projects reflect team experience', function () {
    $project = Project::factory()->create([
        'team_has_disability_or_deaf_lived_experience' => true,
    ]);

    expect($project->teamExperience())->toEqual('Our team has people with lived and living experiences of disability or being Deaf.');

    $project->update([
        'team_has_disability_or_deaf_lived_experience' => false,
    ]);

    $project = $project->fresh();

    expect($project->teamExperience())->toEqual('Our team does not have people with lived and living experiences of disability or being Deaf.');
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

    actingAs($individualUser)->get(localized_route('projects.my-projects'))
        ->assertOk()
        ->assertDontSee('Involved in as a Community Connector')
        ->assertDontSee('Involved in as a Consultation Participant');

    actingAs($individualUser)->get(localized_route('projects.my-running-projects'))->assertNotFound();

    actingAs($individualUser)->get(localized_route('projects.my-contracted-projects'))->assertNotFound();

    $individual->roles = ['participant', 'consultant'];
    $individual->save();
    $individualUser = $individualUser->fresh();

    actingAs($individualUser)->get(localized_route('projects.my-projects'))
        ->assertOk()
        ->assertSee('Involved in as a Community Connector');

    actingAs($individualUser)->get(localized_route('projects.my-contracted-projects'))->assertOk();

    $individual->roles = ['consultant'];
    $individual->save();
    $individualUser = $individualUser->fresh();

    actingAs($individualUser)->get(localized_route('projects.my-projects'))
        ->assertOk()
        ->assertDontSee('Involved in as a Consultation Participant')
        ->assertDontSee('Involved in as a Community Connector');

    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => TeamRole::Administrator->value])
        ->create();
    $regulatedOrganizationUser = $regulatedOrganizationUser->fresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-projects'))
        ->assertOk()
        ->assertSee('Projects I am running')
        ->assertDontSee('Involved in as a Community Connector')
        ->assertDontSee('Involved in as a Consultation Participant');

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-contracted-projects'))->assertNotFound();

    actingAs($regulatedOrganizationUser)->get(localized_route('projects.my-participating-projects'))->assertNotFound();

    $organizationUser = User::factory()->create(['context' => UserContext::Organization->value]);
    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => TeamRole::Administrator->value])
        ->create();
    $organizationUser = $organizationUser->fresh();

    actingAs($organizationUser)->get(localized_route('projects.my-projects'))
        ->assertOk()
        ->assertSee('Projects I am running')
        ->assertDontSee('Involved in as a Community Connector')
        ->assertDontSee('Involved in as a Consultation Participant');

    actingAs($organizationUser)->get(localized_route('projects.my-contracted-projects'))->assertNotFound();

    actingAs($organizationUser)->get(localized_route('projects.my-participating-projects'))->assertNotFound();

    actingAs($organizationUser)->get(localized_route('projects.my-running-projects'))->assertOk();

    $organization->roles = ['connector'];
    $organization->save();
    $organizationUser = $organizationUser->fresh();

    actingAs($organizationUser)->get(localized_route('projects.my-projects'))
        ->assertOk()
        ->assertSee('Projects I am running')
        ->assertSee('Involved in as a Community Connector')
        ->assertDontSee('Involved in as a Consultation Participant');

    actingAs($organizationUser)->get(localized_route('projects.my-participating-projects'))->assertNotFound();

    actingAs($organizationUser)->get(localized_route('projects.my-contracted-projects'))->assertOk();

    actingAs($organizationUser)->get(localized_route('projects.my-running-projects'))->assertOk();

    $organization->roles = ['connector', 'participant'];
    $organization->save();
    $organizationUser = $organizationUser->fresh();

    actingAs($organizationUser)->get(localized_route('projects.my-projects'))
        ->assertOk()
        ->assertSee('Projects I am running')
        ->assertSee('Involved in as a Community Connector')
        ->assertSee('Involved in as a Consultation Participant');

    actingAs($organizationUser)->get(localized_route('projects.my-participating-projects'))->assertOk();

    actingAs($organizationUser)->get(localized_route('projects.my-contracted-projects'))->assertOk();

    actingAs($organizationUser)->get(localized_route('projects.my-running-projects'))->assertOk();

    $organization->roles = ['participant'];
    $organization->save();
    $organizationUser = $organizationUser->fresh();

    actingAs($organizationUser)->get(localized_route('projects.my-projects'))
        ->assertOk()
        ->assertSee('Projects I am running')
        ->assertDontSee('Involved in as a Community Connector')
        ->assertSee('Involved in as a Consultation Participant');

    $traineeUser = User::factory()->create(['context' => UserContext::TrainingParticipant->value]);
    actingAs($traineeUser)->get(localized_route('projects.my-projects'))->assertForbidden();
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

test('test project seekingDisabilityAndDeafGroups scope', function () {
    $this->seed(IdentitySeeder::class);

    $disabilityTypeDeaf = Identity::where('name->en', 'Deaf')->first();
    $projectSeekingDeafExperienceName = 'Project Seeking Deaf Experience';
    $projectSeekingDeafExperience = Project::factory()->create([
        'name->en' => $projectSeekingDeafExperienceName,
    ]);
    $engagementSeekingDeafExperience = Engagement::factory()->create(['project_id' => $projectSeekingDeafExperience->id]);
    $matchingStrategySeekingDeafExperience = MatchingStrategy::factory()->create([
        'matchable_type' => 'App\Models\Engagement',
        'matchable_id' => $engagementSeekingDeafExperience->id,
    ]);
    $matchingStrategySeekingDeafExperience->identities()->attach($disabilityTypeDeaf->id);

    expect($matchingStrategySeekingDeafExperience->matchable->is($engagementSeekingDeafExperience))->toBeTrue;

    $disabilityTypeCognitive = Identity::where('name->en', 'Cognitive disabilities')->first();
    $projectSeekingCognitiveDisabilityExperienceName = 'Project Seeking Cognitive Disability Experience';
    $projectSeekingCognitiveDisabilityExperience = Project::factory()->create([
        'name->en' => $projectSeekingCognitiveDisabilityExperienceName,
    ]);
    $engagementSeekingCognitiveDisabilityExperience = Engagement::factory()->create(['project_id' => $projectSeekingCognitiveDisabilityExperience->id]);
    $matchingStrategySeekingCognitiveDisabilityExperience = MatchingStrategy::factory()->create([
        'matchable_type' => 'App\Models\Engagement',
        'matchable_id' => $engagementSeekingCognitiveDisabilityExperience->id,
    ]);
    $matchingStrategySeekingCognitiveDisabilityExperience->identities()->attach($disabilityTypeCognitive->id);

    $seekingGroupQuery = Project::seekingDisabilityAndDeafGroups([$disabilityTypeDeaf->id])->get();

    expect($seekingGroupQuery->contains($projectSeekingDeafExperience))->toBeTrue();
    expect($seekingGroupQuery->contains($projectSeekingCognitiveDisabilityExperience))->toBeFalse();

    $seekingGroupQuery = Project::seekingDisabilityAndDeafGroups([$disabilityTypeCognitive->id])->get();
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
    $regulatedPrivateSector = Sector::where('name->en', 'Federally Regulated private sector')->first();
    $privateRegulatedOrganization = RegulatedOrganization::factory()->create();
    $privateRegulatedOrganization->sectors()->save($regulatedPrivateSector);
    $regulatedPrivateProject = Project::factory()->create(['projectable_id' => $privateRegulatedOrganization->id]);

    $parliamentarySector = Sector::where('name->en', 'Parliamentary entities')->first();
    $parliamentaryOrganization = RegulatedOrganization::factory()->create();
    $parliamentaryOrganization->sectors()->save($parliamentarySector);
    $parliamentaryProject = Project::factory()->create(['projectable_id' => $parliamentaryOrganization->id]);

    $sectorQuery = Project::sectors([$regulatedPrivateSector->id])->get();

    expect($sectorQuery->contains($regulatedPrivateProject))->toBeTrue();
    expect($sectorQuery->contains($parliamentaryProject))->toBeFalse();

    $sectorQuery = Project::sectors([$parliamentarySector->id])->get();

    expect($sectorQuery->contains($parliamentaryProject))->toBeTrue();
    expect($sectorQuery->contains($regulatedPrivateProject))->toBeFalse();

    $sectorQuery = Project::sectors([$regulatedPrivateSector->id, $parliamentarySector->id])->get();

    expect($sectorQuery->contains($regulatedPrivateProject))->toBeTrue();
    expect($sectorQuery->contains($parliamentaryProject))->toBeTrue();
});

test('test project areas of impact scope', function () {
    $this->seed(ImpactSeeder::class);
    $employmentImpact = Impact::where('name->en', 'Employment')->first();
    $employmentImpactProject = Project::factory()->create();
    $employmentImpactProject->impacts()->attach($employmentImpact->id);

    $communicationImpact = Impact::where('name->en', 'Communications')->first();
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

test('projects can have matching strategies', function () {
    $project = Project::factory()->create();
    $matchingStrategy = MatchingStrategy::factory()->create();
    $project->matchingStrategy()->save($matchingStrategy);

    expect($project->matchingStrategy->id)->toEqual($matchingStrategy->id);
});
