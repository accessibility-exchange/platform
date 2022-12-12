<?php

namespace Tests\Feature;

use App\Filament\Resources\ResourceCollectionResource;
use App\Filament\Resources\ResourceCollectionResource\Pages\ListResourceCollections;
use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Support\Facades\App;
use function Pest\Livewire\livewire;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

test('resource collections can be translated', function () {
    $resourceCollection = ResourceCollection::factory()->create();

    $titleTranslations = ['en' => 'title in English', 'fr' => 'title in French'];
    $descriptionTranslations = ['en' => 'description in English', 'fr' => 'description in French'];

    $resourceCollection->setTranslation('title', 'en', $titleTranslations['en']);
    $resourceCollection->setTranslation('title', 'fr', $titleTranslations['fr']);

    $resourceCollection->setTranslation('description', 'en', $descriptionTranslations['en']);
    $resourceCollection->setTranslation('description', 'fr', $descriptionTranslations['fr']);

    $this->assertEquals($titleTranslations['en'], $resourceCollection->title);
    $this->assertEquals($descriptionTranslations['en'], $resourceCollection->description);
    App::setLocale('fr');
    $this->assertEquals($titleTranslations['fr'], $resourceCollection->title);
    $this->assertEquals($descriptionTranslations['fr'], $resourceCollection->description);

    $this->assertEquals($titleTranslations['en'], $resourceCollection->getTranslation('title', 'en'));
    $this->assertEquals($descriptionTranslations['en'], $resourceCollection->getTranslation('description', 'en'));
    $this->assertEquals($titleTranslations['fr'], $resourceCollection->getTranslation('title', 'fr'));
    $this->assertEquals($descriptionTranslations['fr'], $resourceCollection->getTranslation('description', 'fr'));

    $this->assertEquals($titleTranslations, $resourceCollection->getTranslations('title'));
    $this->assertEquals($descriptionTranslations, $resourceCollection->getTranslations('description'));

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

test('only administrative users can access resource collection admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $this->actingAs($user)->get(ResourceCollectionResource::getUrl('index'))->assertForbidden();
    $this->actingAs($administrator)->get(ResourceCollectionResource::getUrl('index'))->assertSuccessful();

    $this->actingAs($user)->get(ResourceCollectionResource::getUrl('create'))->assertForbidden();
    $this->actingAs($administrator)->get(ResourceCollectionResource::getUrl('create'))->assertSuccessful();

    $this->actingAs($user)->get(ResourceCollectionResource::getUrl('edit', [
        'record' => ResourceCollection::factory()->create(),
    ]))->assertForbidden();

    $this->actingAs($administrator)->get(ResourceCollectionResource::getUrl('edit', [
        'record' => ResourceCollection::factory()->create(),
    ]))->assertSuccessful();
});

test('resource collections can be listed', function () {
    $resourceCollections = ResourceCollection::factory()->count(2)->create();

    livewire(ListResourceCollections::class)->assertCanSeeTableRecords($resourceCollections);
});
