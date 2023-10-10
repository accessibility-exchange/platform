<?php

use App\Http\Livewire\EstimateRequester;
use App\Models\Project;
use App\Models\User;
use App\Notifications\EstimateRequested;

use function Pest\Livewire\livewire;

test('unauthorized user cannot request an estimate', function () {
    $project = Project::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user);

    livewire(EstimateRequester::class, ['model' => $project])
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

    livewire(EstimateRequester::class, ['model' => $project])
        ->call('updateStatus');

    expect($project->fresh()->estimate_requested_at)->toBeTruthy();

    $notification = new EstimateRequested($project);
    $this->assertStringContainsString("{$regulatedOrganization->name} has requested an estimate for their project", $notification->toMail($administrator)->render());
    $this->assertStringContainsString($project->name, $notification->toMail($administrator)->render());

    $administrator = $administrator->fresh();

    expect($administrator->unreadNotifications)->toHaveCount(1);
    $notification = $administrator->unreadNotifications->first();

    $response = $this->actingAs($administrator)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('New estimate request');

    expect($notification->type)->toEqual('App\Notifications\EstimateRequested');
    expect($notification->data['project_id'])->toEqual($project->id);
});
