<?php

use App\Enums\UserContext;
use App\Filament\Resources\LibraryResource;
use App\Filament\Resources\LibraryResource\Pages\EditLibrary;
use App\Filament\Resources\LibraryResource\Pages\ListLibraries;
use App\Filament\Resources\LibraryResource\RelationManagers\ResourceCollectionsRelationManager;
use App\Models\Library;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('only administrative users can access library admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => UserContext::Administrator->value]);
    $library = Library::factory()->create();

    actingAs($user)->get(LibraryResource::getUrl('index'))->assertForbidden();
    actingAs($administrator)->get(LibraryResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(LibraryResource::getUrl('create'))->assertForbidden();
    actingAs($administrator)->get(LibraryResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(LibraryResource::getUrl('edit', [
        'record' => Library::factory()->create(),
    ]))->assertForbidden();

    actingAs($administrator)->get(LibraryResource::getUrl('edit', [
        'record' => Library::factory()->create(),
    ]))->assertSuccessful();

    actingAs($administrator)->livewire(ResourceCollectionsRelationManager::class, [
        'ownerRecord' => $library,
        'pageClass' => EditLibrary::class,
    ])
        ->assertSuccessful();
});

test('libraries can be listed', function () {
    $libraries = Library::factory()->count(2)->create();

    livewire(ListLibraries::class)->assertCanSeeTableRecords($libraries);
});
