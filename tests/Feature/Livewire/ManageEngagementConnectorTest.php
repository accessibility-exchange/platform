<?php

use App\Http\Livewire\ManageEngagementConnector;
use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;

test('engagement consultant management page can be rendered and connector can be sought', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $response = $this->actingAs($user)->get(localized_route('engagements.manage-connector', $engagement));
    $response->assertOk();

    $this->livewire(ManageEngagementConnector::class, ['engagement' => $engagement])
        ->assertSet('project', $engagement->project)
        ->assertSet('seeking_community_connector', false)
        ->set('seeking_community_connector', true)
        ->call('updateStatus');

    $engagement = $engagement->fresh();

    expect($engagement->extra_attributes->get('seeking_community_connector'))->toBeTrue();
});

test('connector invitations can be cancelled', function () {
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

    $this->actingAs($regulatedOrganizationUser);

    $this->livewire(ManageEngagementConnector::class, [
        'engagement' => $engagement,
    ])
        ->assertSee($individual->name)
        ->assertSee('Cancel')
        ->call('cancelInvitation');

    $this->assertModelMissing($invitation);
});

test('individual connector can be removed', function () {
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

    $engagement->connector()->associate($individual);

    $this->livewire(ManageEngagementConnector::class, [
        'engagement' => $engagement,
    ])
        ->assertSee($individual->name)
        ->assertSee('Remove')
        ->call('removeConnector');

    $engagement = $engagement->fresh();
    expect($engagement->connector)->toBeNull();
});

test('organizational connector can be removed', function () {
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

    $engagement->organizationalConnector()->associate($organization);

    $this->livewire(ManageEngagementConnector::class, [
        'engagement' => $engagement,
    ])
        ->assertSee($organization->name)
        ->assertSee('Remove')
        ->call('removeConnector');

    $engagement = $engagement->fresh();
    expect($engagement->organizationalConnector)->toBeNull();
});
