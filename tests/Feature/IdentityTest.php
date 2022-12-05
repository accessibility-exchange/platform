<?php

use App\Enums\IdentityCluster;
use App\Filament\Resources\IdentityResource;
use App\Filament\Resources\IdentityResource\Pages\ListIdentities;
use App\Models\Identity;
use App\Models\User;
use Database\Seeders\IdentitySeeder;
use function Pest\Livewire\livewire;
use Spatie\LaravelOptions\Options;

beforeEach(function () {
    $this->seed(IdentitySeeder::class);
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

test('only administrative users can access identity admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $this->actingAs($user)->get(IdentityResource::getUrl('index'))->assertForbidden();
    $this->actingAs($administrator)->get(IdentityResource::getUrl('index'))->assertSuccessful();
});

test('identities can be listed', function () {
    $administrator = User::factory()->create(['context' => 'administrator']);

    $this->actingAs($administrator);

    $identity = Identity::first();

    livewire(ListIdentities::class)
        ->assertCanSeeTableRecords(collect([$identity]));
});
