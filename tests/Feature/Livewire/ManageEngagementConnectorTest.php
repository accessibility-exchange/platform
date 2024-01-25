<?php

use App\Livewire\ManageEngagementConnector;
use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelMissing;
use function Pest\Livewire\livewire;

test('engagement consultant management page can be rendered and connector can be sought', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    actingAs($user)->get(localized_route('engagements.manage-connector', $engagement))
        ->assertOk();

    livewire(ManageEngagementConnector::class, ['engagement' => $engagement])
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
    $user->individual->update(['roles' => ['connector'], 'region' => 'NS', 'locality' => 'Bridgewater']);
    $user->individual->publish();
    $individual = $user->individual->fresh();

    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => 'connector',
        'type' => 'individual',
        'email' => $individual->user->email,
    ]);

    actingAs($regulatedOrganizationUser);

    livewire(ManageEngagementConnector::class, [
        'engagement' => $engagement,
    ])
        ->assertSee($individual->name)
        ->assertSee('Cancel')
        ->call('cancelInvitation');

    assertModelMissing($invitation);
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
    $user->individual->update(['roles' => ['connector'], 'region' => 'NS', 'locality' => 'Bridgewater']);
    $user->individual->publish();
    $individual = $user->individual->fresh();

    $engagement->connector()->associate($individual);

    actingAs($regulatedOrganizationUser)->
    livewire(ManageEngagementConnector::class, [
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

    $organization = Organization::factory()->create(['roles' => ['consultant'], 'published_at' => now(), 'region' => 'AB', 'locality' => 'Medicine Hat']);

    $engagement->organizationalConnector()->associate($organization);

    actingAs($regulatedOrganizationUser)->
    livewire(ManageEngagementConnector::class, [
        'engagement' => $engagement,
    ])
        ->assertSee($organization->name)
        ->assertSee('Remove')
        ->call('removeConnector');

    $engagement = $engagement->fresh();
    expect($engagement->organizationalConnector)->toBeNull();
});
