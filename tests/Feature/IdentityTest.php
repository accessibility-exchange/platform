<?php

use App\Enums\IdentityCluster;
use App\Models\Identity;
use Database\Seeders\IdentitySeeder;
use Spatie\LaravelOptions\Options;

use function Pest\Laravel\seed;

beforeEach(function () {
    seed(IdentitySeeder::class);
});

test('identities can be grouped by cluster', function () {
    $deafIdentity = Identity::firstWhere('name->en', 'Deaf');

    $disabilityAndDeafIdentities = Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->get();
    $genderIdentities = Identity::whereJsonContains('clusters', IdentityCluster::Gender)->get();

    expect($disabilityAndDeafIdentities->contains($deafIdentity))->toBeTrue();
    expect($genderIdentities->contains($deafIdentity))->toBeFalse();
});

test('identity clusters can be used as options', function () {
    expect(Options::forEnum(IdentityCluster::class)->toArray())->toContain(['label' => 'Age group', 'value' => 'age']);
});
