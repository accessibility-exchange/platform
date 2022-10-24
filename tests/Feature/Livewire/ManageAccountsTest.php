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
