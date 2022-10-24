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

    $this->regulatedOrganization = RegulatedOrganization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
    ]);

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
