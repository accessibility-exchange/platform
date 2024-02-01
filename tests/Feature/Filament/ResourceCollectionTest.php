<?php

use App\Filament\Resources\ResourceCollectionResource;
use App\Filament\Resources\ResourceCollectionResource\Pages\EditResourceCollection;
use App\Filament\Resources\ResourceCollectionResource\Pages\ListResourceCollections;
use App\Filament\Resources\ResourceCollectionResource\RelationManagers\ResourcesRelationManager;
use App\Models\ResourceCollection;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access resource collection admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);
    $resourceCollection = ResourceCollection::factory()->create();

    actingAs($user)->get(ResourceCollectionResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(ResourceCollectionResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(ResourceCollectionResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(ResourceCollectionResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(ResourceCollectionResource::getUrl('edit', [
        'record' => ResourceCollection::factory()->create(),
    ]))->assertForbidden();

    actingAs($administrator)->get(ResourceCollectionResource::getUrl('edit', [
        'record' => ResourceCollection::factory()->create(),
    ]))->assertSuccessful();

    actingAs($administrator)->livewire(ResourcesRelationManager::class, [
        'ownerRecord' => $resourceCollection,
        'pageClass' => EditResourceCollection::class,
    ])
        ->assertSuccessful();
});

test('resource collections can be listed', function () {
    $resourceCollections = ResourceCollection::factory()->count(2)->create();

    livewire(ListResourceCollections::class)->assertCanSeeTableRecords($resourceCollections);
});
