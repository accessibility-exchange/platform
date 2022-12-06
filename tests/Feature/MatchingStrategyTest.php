<?php

use App\Enums\IdentityCluster;
use App\Models\Identity;
use App\Models\MatchingStrategy;
use App\Models\User;
use Database\Seeders\IdentitySeeder;

beforeEach(function () {
    $this->seed(IdentitySeeder::class);
    $this->strategy = MatchingStrategy::factory()->create();
});

test('administrators can access matching strategies', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    expect($user->can('view', $this->strategy))->toBeFalse();
    expect($administrator->can('view', $this->strategy))->toBeTrue();
});

test('matching strategy can be checked for presence of identities', function () {
    $age = Identity::whereJsonContains('clusters', IdentityCluster::Age)->first();
    $disability = Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first();
    $gender = Identity::whereJsonContains('clusters', IdentityCluster::Gender)->first();
    $ethnoracial = Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->first();

    $this->strategy->identities()->attach([
        $age->id,
        $disability->id,
    ]);

    $this->strategy->refresh();

    expect($this->strategy->hasIdentities([
        $age,
        $disability,
    ]))->toBeTrue();

    expect($this->strategy->hasIdentities([
        $gender,
        $ethnoracial,
    ]))->toBeFalse();
});

test('related identities can be synced to a matching strategy', function () {
    $ages = Identity::whereJsonContains('clusters', IdentityCluster::Age)->get();
    $disability = Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first();
    $gender = Identity::whereJsonContains('clusters', IdentityCluster::Gender)->first();
    $ethnoracial = Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->first();

    $this->strategy->identities()->attach([
        $ages->first()->id,
        $disability->id,
        $gender->id,
        $ethnoracial->id,
    ]);

    $this->strategy->refresh();

    $this->strategy->syncRelatedIdentities(IdentityCluster::Age, $ages->last()->id);

    $this->strategy->refresh();

    expect($this->strategy->hasIdentity($ages->first()))->toBeFalse();
    expect($this->strategy->hasIdentity($ages->last()))->toBeTrue();

    $this->strategy->syncRelatedIdentities(IdentityCluster::Age, $ages->pluck('id')->toArray());

    $this->strategy->refresh();

    expect($this->strategy->ageBrackets->count())->toEqual(4);
});

test('mutually exclusive identities can be synced to a matching strategy', function () {
    $ages = Identity::whereJsonContains('clusters', IdentityCluster::Age)->get();
    $disability = Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first();
    $gender = Identity::whereJsonContains('clusters', IdentityCluster::Gender)->first();
    $ethnoracial = Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->first();

    $this->strategy->identities()->attach([
        $ages->first()->id,
        $disability->id,
        $gender->id,
        $ethnoracial->id,
    ]);

    $this->strategy->refresh();

    $this->strategy->syncMutuallyExclusiveIdentities(IdentityCluster::Age, $ages->last()->id, [IdentityCluster::Gender, IdentityCluster::Ethnoracial]);

    $this->strategy->refresh();

    expect($this->strategy->hasIdentity($ages->first()))->toBeFalse();
    expect($this->strategy->hasIdentity($ages->last()))->toBeTrue();
    expect($this->strategy->hasIdentity($disability))->toBeTrue();
    expect($this->strategy->hasIdentity($gender))->toBeFalse();
    expect($this->strategy->hasIdentity($ethnoracial))->toBeFalse();

    $this->strategy->identities()->attach([
        $ages->first()->id,
        $disability->id,
        $gender->id,
        $ethnoracial->id,
    ]);

    $this->strategy->syncMutuallyExclusiveIdentities(IdentityCluster::Age, $ages->pluck('id')->toArray(), [IdentityCluster::Gender, IdentityCluster::Ethnoracial]);

    $this->strategy->refresh();

    expect($this->strategy->ageBrackets->count())->toEqual(4);
    expect($this->strategy->hasIdentity($disability))->toBeTrue();
    expect($this->strategy->hasIdentity($gender))->toBeFalse();
    expect($this->strategy->hasIdentity($ethnoracial))->toBeFalse();
});
