<?php

use App\Filament\Resources\IdentityResource;
use App\Filament\Resources\IdentityResource\Pages\ListIdentities;
use App\Models\Identity;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access identity admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);
    $identity = Identity::factory()->create();

    actingAs($user)->get(IdentityResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(IdentityResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(IdentityResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(IdentityResource::getUrl('create'))->assertSuccessful();
});

test('identities can be listed', function () {
    $administrator = User::factory()->create(['context' => 'administrator']);

    actingAs($administrator);

    $identities = Identity::factory(5)->create();

    livewire(ListIdentities::class)
        ->assertCanSeeTableRecords($identities);
});
