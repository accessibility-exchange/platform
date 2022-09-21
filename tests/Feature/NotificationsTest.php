<?php

use App\Http\Livewire\MarkNotificationAsRead;
use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Notifications\AgreementReceived;
use App\Notifications\OrganizationalContractorInvited;
use function Pest\Livewire\livewire;

test('organizational users see merged notifications for their organizations and projects', function () {
    $organization = Organization::factory()->create(['roles' => ['connector']]);
    $project = Project::factory()->create([
        'projectable_id' => $organization->id,
        'projectable_type' => 'App\Models\Organization',
    ]);
    $engagement = Engagement::factory()->create(['project_id' => $project->id]);

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

    $response = $this->actingAs($organizationAdministrator)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your agreement has been received');
    $response->assertSee('Your organization has been invited as a Community Connector');

    $this->actingAs($organizationAdministrator);

    livewire(MarkNotificationAsRead::class, ['notification' => $organizationAdministrator->allUnreadNotifications()->first()])
        ->call('markAsRead');

    $organizationAdministrator = $organizationAdministrator->fresh();

    expect($organizationAdministrator->allUnreadNotifications())->toHaveCount(1);
    expect($organizationAdministrator->allNotifications())->toHaveCount(2);

    $response = $this->actingAs($organizationAdministrator)->get(localized_route('dashboard.notifications'));
    $response->assertOk();

    $response->assertSee('Your agreement has been received');
    $response->assertDontSee('Your organization has been invited as a Community Connector');

    $response = $this->actingAs($organizationAdministrator)->get(localized_route('dashboard.notifications'));
    $response->assertOk();

    $response->assertSee('Your agreement has been received');
    $response->assertSee('Your organization has been invited as a Community Connector');
});
