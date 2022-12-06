<?php

use App\Enums\IdentityCluster;
use App\Models\Identity;
use App\Models\MatchingStrategy;
use App\Models\User;
use Database\Seeders\IdentitySeeder;

test('administrators can access matching strategies', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $strategy = MatchingStrategy::factory()->create();

    expect($user->can('view', $strategy))->toBeFalse();
    expect($administrator->can('view', $strategy))->toBeTrue();
});

test('matching strategy can be checked for presence of identities', function () {
    $this->seed(IdentitySeeder::class);

    $strategy = MatchingStrategy::factory()->create();

    $age = Identity::whereJsonContains('clusters', IdentityCluster::Age)->first();
    $disability = Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first();
    $gender = Identity::whereJsonContains('clusters', IdentityCluster::Gender)->first();
    $ethnoracial = Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->first();

    $strategy->identities()->attach([
        $age->id,
        $disability->id,
    ]);

    $strategy = $strategy->fresh();

    expect($strategy->hasIdentities([
        $age,
        $disability,
    ]))->toBeTrue();

    expect($strategy->hasIdentities([
        $gender,
        $ethnoracial,
    ]))->toBeFalse();
});
