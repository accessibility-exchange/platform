<?php

use App\Http\Livewire\EstimateApprover;
use App\Models\Project;
use App\Models\User;

test('unauthorized user cannot approve an estimate', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->livewire(EstimateApprover::class, ['model' => $project])
        ->call('updateStatus')
        ->assertForbidden();

    expect($project->fresh()->estimate_approved_at)->toBeNull();
});

test('authorized user can approve an estimate', function () {
    $administrator = User::factory()->create(['context' => 'administrator']);

    $project = Project::factory()->create();
    $regulatedOrganization = $project->projectable;
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $this->actingAs($user);

    $this->livewire(EstimateApprover::class, ['model' => $project])
        ->call('updateStatus');

    expect($project->fresh()->estimate_approved_at)->toBeTruthy();

    $notification = $administrator->fresh()->notifications->first();

    expect($notification->type)->toEqual('App\Notifications\EstimateApproved');
    expect($notification->data['project_id'])->toEqual($project->id);
});
