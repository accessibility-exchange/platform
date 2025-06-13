<?php

use App\Models\Library;
use App\Models\Resource;
use App\Models\ResourceCollection;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('resource collections can be translated', function () {
    $library = Library::factory()->create();

    $titleTranslations = ['en' => 'title in English', 'fr' => 'title in French'];
    $descriptionTranslations = ['en' => 'description in English', 'fr' => 'description in French'];

    $library->setTranslation('title', 'en', $titleTranslations['en']);
    $library->setTranslation('title', 'fr', $titleTranslations['fr']);

    $library->setTranslation('description', 'en', $descriptionTranslations['en']);
    $library->setTranslation('description', 'fr', $descriptionTranslations['fr']);

    expect($library->title)->toEqual($titleTranslations['en']);
    expect($library->description)->toEqual($descriptionTranslations['en']);
    App::setLocale('fr');
    expect($library->title)->toEqual($titleTranslations['fr']);
    expect($library->description)->toEqual($descriptionTranslations['fr']);

    expect($library->getTranslation('title', 'en'))->toEqual($titleTranslations['en']);
    expect($library->getTranslation('description', 'en'))->toEqual($descriptionTranslations['en']);
    expect($library->getTranslation('title', 'fr'))->toEqual($titleTranslations['fr']);
    expect($library->getTranslation('description', 'fr'))->toEqual($descriptionTranslations['fr']);

    expect($library->getTranslations('title'))->toEqual($titleTranslations);
    expect($library->getTranslations('description'))->toEqual($descriptionTranslations);

    $this->expectException(AttributeIsNotTranslatable::class);
    $library->setTranslation('user_id', 'en', 'user_id in English');
});

test('many resource collections can belong in single library', function () {
    $library = Library::factory()->create();

    $resourceCollections = ResourceCollection::factory(3)->create();

    foreach ($resourceCollections as $resourceCollection) {
        $library->resourceCollections()->sync($resourceCollection->id);
        assertDatabaseHas('library_resource_collection', [
            'library_id' => $library->id,
            'resource_collection_id' => $resourceCollection->id,
        ]);
    }

    expect($resourceCollection->libraries->first()->id)->toBe($library->id);
});

test('deleting resource collections belonging to library removes them from the library', function () {
    $library = Library::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create();
    $library->resourceCollections()->sync($resourceCollection->id);

    assertDatabaseHas('library_resource_collection', [
        'library_id' => $library->id,
        'resource_collection_id' => $resourceCollection->id,
    ]);

    $resourceCollection->delete();

    assertDatabaseMissing('library_resource_collection', [
        'library_id' => $library->id,
        'resource_collection_id' => $resourceCollection->id,
    ]);

    expect($library->resourceCollections->count())->toBe(0);
});

test('many resources can belong in single library', function () {
    $library = Library::factory()->create();

    $resources = Resource::factory(3)->create();

    foreach ($resources as $resource) {
        $library->resources()->sync($resource->id);
        assertDatabaseHas('library_resource', [
            'library_id' => $library->id,
            'resource_id' => $resource->id,
        ]);
    }

    expect($resource->libraries->first()->id)->toBe($library->id);
});

test('deleting resources belonging to library removes them from the library', function () {
    $library = Library::factory()->create();
    $resource = Resource::factory()->create();
    $library->resources()->sync($resource->id);

    assertDatabaseHas('library_resource', [
        'library_id' => $library->id,
        'resource_id' => $resource->id,
    ]);

    $resource->delete();

    assertDatabaseMissing('library_resource', [
        'library_id' => $library->id,
        'resource_id' => $resource->id,
    ]);

    expect($library->resources->count())->toBe(0);
});
