<?php

use App\Http\Livewire\AdminEstimatesAndAgreements;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\AgreementReceived;
use App\Notifications\EstimateReturned;
use function Pest\Livewire\livewire;

test('estimates and agreements appear in expected order', function () {
    $datetime = now();

    $estimateRequestedProject = Project::factory()->create([
        'estimate_requested_at' => $datetime,
        'name' => 'Project with estimate requested',
    ]);
    $estimateReturnedProject = Project::factory()->create([
        'estimate_requested_at' => $datetime,
        'estimate_returned_at' => $datetime,
        'name' => 'Project with estimate returned',
    ]);
    $estimateApprovedProject = Project::factory()->create([
        'estimate_requested_at' => $datetime,
        'estimate_returned_at' => $datetime,
        'estimate_approved_at' => $datetime,
        'name' => 'Project with estimate approved',
    ]);
    $agreementReceivedProject = Project::factory()->create([
        'estimate_requested_at' => $datetime,
        'estimate_returned_at' => $datetime,
        'estimate_approved_at' => $datetime,
        'agreement_received_at' => $datetime,
        'name' => 'Project with agreement received',
    ]);

    livewire(AdminEstimatesAndAgreements::class)
        ->assertSeeInOrder([
            'Project with estimate requested',
            'Project with estimate returned',
            'Project with estimate approved',
            'Project with agreement received',
        ]);
});

test('estimate can be marked as returned', function () {
    $administrator = User::factory()->create(['context' => 'administrator']);

    $project = Project::factory()->create([
        'estimate_requested_at' => now(),
    ]);

    $projectManager = User::factory()->create(['context' => 'regulated-organization']);
    $project->projectable->users()->attach($projectManager, ['role' => 'admin']);

    $this->actingAs($administrator);

    livewire(AdminEstimatesAndAgreements::class)
        ->assertSee($project->name)
        ->call('markEstimateReturned', $project->id)
        ->assertDontSee('Mark estimate as returned')
        ->assertSee('Estimate returned')
        ->assertSee('Agreement pending')
        ->assertSee('Mark agreement as received');

    $notification = new EstimateReturned($project);
    $rendered = $notification->toMail($project)->render();
    $this->assertStringContainsString('Your estimate for', $rendered);
    $this->assertStringContainsString('along with a project agreement for you to sign, has been sent to', $rendered);
    $this->assertStringContainsString("Your estimate has been returned for {$project->name}, along with a project agreement for you to sign.", $notification->toVonage($project)->content);

    expect($project->unreadNotifications)->toHaveCount(1);
    expect($project->unreadNotifications->first()->type)->toEqual('App\Notifications\EstimateReturned');

    $response = $this->actingAs($projectManager)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your estimate has been returned');
    $response->assertSee("Your estimate for <strong>{$project->name}</strong>, along with a project agreement for to sign", false);
});

test('agreement can be marked as received', function () {
    $administrator = User::factory()->create(['context' => 'administrator']);

    $datetime = now();

    $project = Project::factory()->create([
        'estimate_requested_at' => $datetime,
        'estimate_returned_at' => $datetime,
        'estimate_approved_at' => $datetime,
    ]);

    $projectManager = User::factory()->create(['context' => 'regulated-organization']);
    $project->projectable->users()->attach($projectManager, ['role' => 'admin']);

    $this->actingAs($administrator);

    livewire(AdminEstimatesAndAgreements::class)
        ->assertSee($project->name)
        ->assertSee('Estimate approved')
        ->assertSee('Agreement pending')
        ->call('markAgreementReceived', $project->id)
        ->assertDontSee('Agreement pending')
        ->assertDontSee('Mark agreement as received')
        ->assertSee('Agreement received');

    $notification = new AgreementReceived($project);
    $this->assertStringContainsString('Your agreement has been received', $notification->toMail($project)->render());
    $this->assertStringContainsString('Your agreement has been received', $notification->toVonage($project)->content);

    expect($project->unreadNotifications)->toHaveCount(1);
    expect($project->unreadNotifications->first()->type)->toEqual('App\Notifications\AgreementReceived');

    $response = $this->actingAs($projectManager)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your agreement has been received');
    $response->assertSee("Your agreement has been received for <strong>{$project->name}</strong>", false);
});

test('projects can be searched by organization name', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => 'Umbrella Corporation']);

    $project = Project::factory()->create([
        'estimate_requested_at' => now(),
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $otherProject = Project::factory()->create(['estimate_requested_at' => now()]);

    livewire(AdminEstimatesAndAgreements::class)
        ->assertSee(localized_route('projects.show', $project))
        ->assertSee(localized_route('projects.show', $otherProject))
        ->set('searchQuery', 'Umbrella')
        ->call('search')
        ->assertSee(localized_route('projects.show', $project))
        ->assertDontSee(localized_route('projects.show', $otherProject))
        ->assertSee('1 result');
});
