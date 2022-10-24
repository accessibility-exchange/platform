<?php

use App\Http\Livewire\ManageAccounts;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->organization = Organization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
    ]);

    $this->organizationUser = User::factory()->create(['context' => 'organization']);
    $this->organization->users()->attach(
        $this->organizationUser,
        ['role' => 'admin']
    );

    $this->regulatedOrganization = RegulatedOrganization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
    ]);

    $this->regulatedOrganizationUser = User::factory()->create(['context' => 'organization']);
    $this->regulatedOrganization->users()->attach(
        $this->regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $this->individualUser = User::factory()->create([
        'oriented_at' => null,
    ]);

    $this->individual = $this->individualUser->individual;
});

test('accounts appear with pending status before approval', function () {
    livewire(ManageAccounts::class)
        ->assertSeeInOrder([
            'Pending approval',
            'Pending approval',
            'Pending approval',
        ]);
});

test('accounts can be approved', function () {
    livewire(ManageAccounts::class)
        ->call('approveAccount', $this->organization->id, 'Organization')
        ->call('approveAccount', $this->regulatedOrganization->id, 'RegulatedOrganization')
        ->call('approveIndividualAccount', $this->individual->id)
        ->assertSeeInOrder([
            'Approved',
            'Approved',
            'Approved',
        ]);
});

test('accounts can be suspended', function () {
    livewire(ManageAccounts::class)
        ->call('suspendAccount', $this->organization->id, 'Organization')
        ->call('suspendAccount', $this->regulatedOrganization->id, 'RegulatedOrganization')
        ->call('suspendIndividualAccount', $this->individual->id)
        ->assertSeeInOrder([
            'Suspended',
            'Suspended',
            'Suspended',
        ]);

    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    expect($this->organizationUser->checkStatus('suspended'))->toBeTrue();
    expect($this->regulatedOrganizationUser->checkStatus('suspended'))->toBeTrue();
});

test('accounts appear with suspended status when suspended', function () {
    foreach ([
        $this->organization,
        $this->regulatedOrganization,
        $this->individualUser,
    ] as $model) {
        $model->update([
            'suspended_at' => now(),
        ]);
    }

    livewire(ManageAccounts::class)
        ->assertSeeInOrder([
            'Suspended',
            'Suspended',
            'Suspended',
        ]);
});

test('accounts can be unsuspended', function () {
    foreach ([
        $this->organization,
        $this->regulatedOrganization,
        $this->individualUser,
    ] as $model) {
        $model->update([
            'oriented_at' => now(),
            'suspended_at' => now(),
        ]);
    }

    foreach ([
        $this->organizationUser,
        $this->regulatedOrganizationUser,
    ] as $model) {
        $model->update([
            'suspended_at' => now(),
        ]);
    }

    foreach ([
        $this->organization,
        $this->regulatedOrganization,
    ] as $model) {
        $model->update([
            'validated_at' => now(),
        ]);
    }

    $this->organization = $this->organization->fresh();
    $this->regulatedOrganization = $this->regulatedOrganization->fresh();
    $this->individualUser = $this->individualUser->fresh();
    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    livewire(ManageAccounts::class)
        ->assertSeeInOrder([
            'Suspended',
            'Suspended',
            'Suspended',
        ])
        ->call('unsuspendAccount', $this->organization->id, 'Organization')
        ->call('unsuspendAccount', $this->regulatedOrganization->id, 'RegulatedOrganization')
        ->call('unsuspendIndividualAccount', $this->individual->id)
        ->assertSeeInOrder([
            'Approved',
            'Approved',
            'Approved',
        ]);

    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    expect($this->organizationUser->checkStatus('suspended'))->toBeFalse();
    expect($this->regulatedOrganizationUser->checkStatus('suspended'))->toBeFalse();
});
