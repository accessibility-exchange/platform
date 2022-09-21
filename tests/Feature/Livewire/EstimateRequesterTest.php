<?php

use App\Http\Livewire\EstimateRequester;
use App\Models\Project;
use App\Models\User;

test('unauthorized user cannot request an estimate', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->livewire(EstimateRequester::class, ['model' => $project])
        ->call('updateStatus')
        ->assertForbidden();

    expect($project->fresh()->estimate_requested_at)->toBeNull();
});

test('authorized user can request an estimate', function () {
    $administrator = User::factory()->create(['context' => 'administrator']);

    $project = Project::factory()->create();
    $regulatedOrganization = $project->projectable;
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $this->actingAs($user);

    $this->livewire(EstimateRequester::class, ['model' => $project])
        ->call('updateStatus');

    expect($project->fresh()->estimate_requested_at)->toBeTruthy();

    $notification = $administrator->fresh()->notifications->first();

    expect($notification->type)->toEqual('App\Notifications\EstimateRequested');
    expect($notification->data['project_id'])->toEqual($project->id);
});
