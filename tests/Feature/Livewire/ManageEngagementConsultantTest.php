<?php

use App\Http\Livewire\ManageEngagementConsultant;
use App\Models\Engagement;

test('engagement consultant management page can be rendered and consultant can be sought', function () {
    $engagement = Engagement::factory()->create();

    $this->livewire(ManageEngagementConsultant::class, ['engagement' => $engagement])
        ->assertSet('project', $engagement->project)
        ->assertSet('seeking_accessibility_consultant', false)
        ->set('seeking_accessibility_consultant', true)
        ->call('updateStatus');

    $engagement = $engagement->fresh();

    expect($engagement->extra_attributes->get('seeking_accessibility_consultant'))->toBeTrue();
});
