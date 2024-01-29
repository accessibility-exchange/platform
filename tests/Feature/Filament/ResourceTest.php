<?php

use App\Filament\Resources\ResourceResource;
use App\Filament\Resources\ResourceResource\Pages\ListResources;
use App\Models\Resource;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access resource admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    actingAs($user)->get(ResourceResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(ResourceResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(ResourceResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(ResourceResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(ResourceResource::getUrl('edit', [
        'record' => Resource::factory()->create(),
    ]))->assertForbidden();

    actingAs($administrator)->get(ResourceResource::getUrl('edit', [
        'record' => Resource::factory()->create(),
    ]))->assertSuccessful();
});

test('resources can be listed', function () {
    $resources = Resource::factory()->count(2)->create();

    livewire(ListResources::class)->assertCanSeeTableRecords($resources);
});
