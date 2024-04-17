<?php

use App\Livewire\MarkNotificationAsRead;
use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\AgreementReceived;
use App\Notifications\OrganizationalContractorInvited;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('organization users see merged notifications for their organizations and projects', function () {
    $organization = Organization::factory()->create(['roles' => ['connector']]);
    $project = Project::factory()->create([
        'projectable_id' => $organization->id,
        'projectable_type' => 'App\Models\Organization',
    ]);
    $engagement = Engagement::factory()->create();

    $organizationAdministrator = User::factory()->create(['context' => 'organization']);
    $organization->users()->attach($organizationAdministrator, ['role' => 'admin']);

    $project->notify(new AgreementReceived($project));
    $organization->notify(new OrganizationalContractorInvited(Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => 'connector',
        'type' => 'organization',
        'email' => $organization->contact_person_email,
    ])));

    expect($organizationAdministrator->allUnreadNotifications())->toHaveCount(2);
    expect($organizationAdministrator->allNotifications())->toHaveCount(2);

    actingAs($organizationAdministrator)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your signed agreement has been received')
        ->assertSee('Your organization has been invited as a Community Connector');

    actingAs($organizationAdministrator);

    livewire(MarkNotificationAsRead::class, ['notification' => $organizationAdministrator->allUnreadNotifications()->first()])
        ->call('markAsRead');

    $organizationAdministrator = $organizationAdministrator->fresh();

    expect($organizationAdministrator->allUnreadNotifications())->toHaveCount(1);
    expect($organizationAdministrator->allNotifications())->toHaveCount(2);

    actingAs($organizationAdministrator)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your signed agreement has been received')
        ->assertDontSee('Your organization has been invited as a Community Connector');

    actingAs($organizationAdministrator)->get(localized_route('dashboard.notifications-all'))
        ->assertOk()
        ->assertSee('Your signed agreement has been received')
        ->assertSee('Your organization has been invited as a Community Connector');
});

test('regulated organization users see merged notifications for their regulated organizations and projects', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $regulatedOrganizationAdministrator = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization->users()->attach($regulatedOrganizationAdministrator, ['role' => 'admin']);

    $project->notify(new AgreementReceived($project));

    $regulatedOrganizationAdministrator = $regulatedOrganizationAdministrator->fresh();

    expect($regulatedOrganizationAdministrator->allUnreadNotifications())->toHaveCount(1);
    expect($regulatedOrganizationAdministrator->allNotifications())->toHaveCount(1);

    actingAs($regulatedOrganizationAdministrator)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your signed agreement has been received');

    actingAs($regulatedOrganizationAdministrator);

    livewire(MarkNotificationAsRead::class, ['notification' => $regulatedOrganizationAdministrator->allUnreadNotifications()->first()])
        ->call('markAsRead');

    $regulatedOrganizationAdministrator = $regulatedOrganizationAdministrator->fresh();

    expect($regulatedOrganizationAdministrator->allUnreadNotifications())->toHaveCount(0);
    expect($regulatedOrganizationAdministrator->allNotifications())->toHaveCount(1);

    actingAs($regulatedOrganizationAdministrator)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertDontSee('Your signed agreement has been received');

    actingAs($regulatedOrganizationAdministrator)->get(localized_route('dashboard.notifications-all'))
        ->assertOk()
        ->assertSee('Your signed agreement has been received');
});
