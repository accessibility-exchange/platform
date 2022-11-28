<?php

use App\Enums\IdentityCluster;
use App\Models\Identity;
use Database\Seeders\IdentitySeeder;

beforeEach(function () {
    $this->seed(IdentitySeeder::class);
});

test('identities can be grouped by cluster', function () {
    $deafIdentity = Identity::firstWhere('name->en', 'Deaf');

    $disabilityAndDeafIdentities = Identity::where('cluster', IdentityCluster::DisabilityAndDeaf)->get();
    $genderIdentities = Identity::where('cluster', IdentityCluster::Gender)->get();

    expect($disabilityAndDeafIdentities->contains($deafIdentity))->toBeTrue();
    expect($genderIdentities->contains($deafIdentity))->toBeFalse();
});
