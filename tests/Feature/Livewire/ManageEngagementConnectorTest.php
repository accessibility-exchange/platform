<?php

use App\Http\Livewire\ManageEngagementConnector;
use App\Models\Engagement;
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
    $this->markTestIncomplete();
});
