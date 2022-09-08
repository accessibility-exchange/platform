<?php

use App\Http\Livewire\ManageEngagementConnector;
use App\Models\Engagement;

test('engagement consultant management page can be rendered and connector can be sought', function () {
    $engagement = Engagement::factory()->create();

    $this->livewire(ManageEngagementConnector::class, ['engagement' => $engagement])
        ->assertSet('project', $engagement->project)
        ->assertSet('seeking_community_connector', false)
        ->set('seeking_community_connector', true)
        ->call('updateStatus');

    $engagement = $engagement->fresh();

    expect($engagement->extra_attributes->get('seeking_community_connector'))->toBeTrue();
});
