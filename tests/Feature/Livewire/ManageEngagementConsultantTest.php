<?php

use App\Http\Livewire\ManageEngagementConsultant;
use App\Models\Engagement;
use App\Models\User;

test('engagement consultant management page can be rendered and consultant can be sought', function () {
    $engagement = Engagement::factory()->create();

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $response = $this->actingAs($user)->get(localized_route('engagements.manage-consultant', $engagement));
    $response->assertOk();

    $this->livewire(ManageEngagementConsultant::class, ['engagement' => $engagement])
        ->assertSet('project', $engagement->project)
        ->assertSet('seeking_accessibility_consultant', false)
        ->set('seeking_accessibility_consultant', true)
        ->call('updateStatus');

    $engagement = $engagement->fresh();

    expect($engagement->extra_attributes->get('seeking_accessibility_consultant'))->toBeTrue();
});
