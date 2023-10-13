<?php

use App\Filament\Resources\ResourceCollectionResource;
use App\Filament\Resources\ResourceCollectionResource\Pages\EditResourceCollection;
use App\Filament\Resources\ResourceCollectionResource\Pages\ListResourceCollections;
use App\Filament\Resources\ResourceCollectionResource\RelationManagers\ResourcesRelationManager;
use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('resource collections can be translated', function () {
    $resourceCollection = ResourceCollection::factory()->create();

    $titleTranslations = ['en' => 'title in English', 'fr' => 'title in French'];
    $descriptionTranslations = ['en' => 'description in English', 'fr' => 'description in French'];

    $resourceCollection->setTranslation('title', 'en', $titleTranslations['en']);
    $resourceCollection->setTranslation('title', 'fr', $titleTranslations['fr']);

    $resourceCollection->setTranslation('description', 'en', $descriptionTranslations['en']);
    $resourceCollection->setTranslation('description', 'fr', $descriptionTranslations['fr']);

    expect($resourceCollection->title)->toEqual($titleTranslations['en']);
    expect($resourceCollection->description)->toEqual($descriptionTranslations['en']);
    App::setLocale('fr');
    expect($resourceCollection->title)->toEqual($titleTranslations['fr']);
    expect($resourceCollection->description)->toEqual($descriptionTranslations['fr']);

    expect($resourceCollection->getTranslation('title', 'en'))->toEqual($titleTranslations['en']);
    expect($resourceCollection->getTranslation('description', 'en'))->toEqual($descriptionTranslations['en']);
    expect($resourceCollection->getTranslation('title', 'fr'))->toEqual($titleTranslations['fr']);
    expect($resourceCollection->getTranslation('description', 'fr'))->toEqual($descriptionTranslations['fr']);

    expect($resourceCollection->getTranslations('title'))->toEqual($titleTranslations);
    expect($resourceCollection->getTranslations('description'))->toEqual($descriptionTranslations);

    $this->expectException(AttributeIsNotTranslatable::class);
    $resourceCollection->setTranslation('user_id', 'en', 'user_id in English');
});

test('many resources can belong in single resource collection', function () {
    $resourceCollection = ResourceCollection::factory()->create();

    $resources = Resource::factory(3)->create();

    foreach ($resources as $resource) {
        $resourceCollection->resources()->sync($resource->id);
        $this->assertDatabaseHas('resource_resource_collection', [
            'resource_collection_id' => $resourceCollection->id,
            'resource_id' => $resource->id,
        ]);
    }
});

test('deleting resources belonging to resource collection removes them from the collection', function () {
    $resourceCollection = ResourceCollection::factory()->create();
    $resource = Resource::factory()->create();
    $resourceCollection->resources()->sync($resource->id);

    $this->assertDatabaseHas('resources', [
        'id' => $resource->id,
    ]);

    $this->assertDatabaseHas('resource_resource_collection', [
        'resource_collection_id' => $resourceCollection->id,
        'resource_id' => $resource->id,
    ]);

    $resource->delete();

    $this->assertDatabaseMissing('resource_resource_collection', [
        'resource_collection_id' => $resourceCollection->id,
        'resource_id' => $resource->id,
    ]);
});

test('users can view resource collections', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);
    $resourceCollection = ResourceCollection::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('resource-collections.index'));
    $response->assertOk();
    $response->assertSee($resourceCollection->title);

    $response = $this->actingAs($user)->get(localized_route('resource-collections.show', $resourceCollection));
    $response->assertOk();
    $response->assertSee($resourceCollection->title);
    $response->assertDontSee('Edit resource collection');

    $response = $this->actingAs($administrator)->get(localized_route('resource-collections.show', $resourceCollection));
    $response->assertOk();
    $response->assertSee('Edit resource collection');
});

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
