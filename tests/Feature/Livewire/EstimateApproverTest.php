<?php

use App\Http\Livewire\EstimateApprover;
use App\Models\Project;
use App\Models\User;
use App\Notifications\EstimateApproved;
use function Pest\Livewire\livewire;

test('unauthorized user cannot approve an estimate', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user);

    livewire(EstimateApprover::class, ['model' => $project])
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

    livewire(EstimateApprover::class, ['model' => $project])
        ->call('updateStatus');

    expect($project->fresh()->estimate_approved_at)->toBeTruthy();

    $notification = new EstimateApproved($project);
    $this->assertStringContainsString("{$regulatedOrganization->name} has approved an estimate for their project", $notification->toMail($administrator)->render());
    $this->assertStringContainsString($project->name, $notification->toMail($administrator)->render());

    $administrator = $administrator->fresh();

    expect($administrator->unreadNotifications)->toHaveCount(1);
    $notification = $administrator->unreadNotifications->first();

    $response = $this->actingAs($administrator)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('New estimate approval');

    expect($notification->type)->toEqual('App\Notifications\EstimateApproved');
    expect($notification->data['project_id'])->toEqual($project->id);
});
