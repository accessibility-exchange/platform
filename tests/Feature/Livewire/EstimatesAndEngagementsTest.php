<?php

use App\Http\Livewire\AdminEstimatesAndAgreements;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use function Pest\Livewire\livewire;

test('estimates and engagements appear in expected order', function () {
    $estimateRequestedProject = Project::factory()->create([
        'estimate_requested_at' => now(),
        'name' => 'Project with estimate requested',
    ]);
    $estimateReturnedProject = Project::factory()->create([
        'estimate_requested_at' => now(),
        'estimate_returned_at' => now(),
        'name' => 'Project with estimate returned',
    ]);
    $estimateApprovedProject = Project::factory()->create([
        'estimate_requested_at' => now(),
        'estimate_returned_at' => now(),
        'estimate_approved_at' => now(),
        'name' => 'Project with estimate approved',
    ]);
    $agreementReceivedProject = Project::factory()->create([
        'estimate_requested_at' => now(),
        'estimate_returned_at' => now(),
        'estimate_approved_at' => now(),
        'agreement_received_at' => now(),
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
    $project = Project::factory()->create([
        'estimate_requested_at' => now(),
    ]);

    livewire(AdminEstimatesAndAgreements::class)
        ->assertSee($project->name)
        ->call('markEstimateReturned', $project->id)
        ->assertDontSee('Mark estimate as returned')
        ->assertSee('Estimate returned')
        ->assertSee('Agreement pending')
        ->assertSee('Mark agreement as received');
});

test('agreement can be marked as received', function () {
    $project = Project::factory()->create([
        'estimate_requested_at' => now(),
        'estimate_returned_at' => now(),
        'estimate_approved_at' => now(),
    ]);

    livewire(AdminEstimatesAndAgreements::class)
        ->assertSee($project->name)
        ->assertSee('Estimate approved')
        ->assertSee('Agreement pending')
        ->call('markAgreementReceived', $project->id)
        ->assertDontSee('Agreement pending')
        ->assertDontSee('Mark agreement as received')
        ->assertSee('Agreement received');
});

test('projects can be searched by organization name', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => 'Umbrella Corporation']);

    $project = Project::factory()->create([
        'estimate_requested_at' => now(),
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $otherProject = Project::factory()->create(['estimate_requested_at' => now()]);

    livewire(AdminEstimatesAndAgreements::class)
        ->assertSee($project->name)
        ->assertSee($otherProject->name)
        ->set('query', 'Umbrella')
        ->call('search')
        ->assertSee($project->name)
        ->assertDontSee($otherProject->name)
        ->assertSee('1 result');
});
