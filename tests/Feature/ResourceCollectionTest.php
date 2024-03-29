<?php

use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

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
        assertDatabaseHas('resource_resource_collection', [
            'resource_collection_id' => $resourceCollection->id,
            'resource_id' => $resource->id,
        ]);
    }
});

test('deleting resources belonging to resource collection removes them from the collection', function () {
    $resourceCollection = ResourceCollection::factory()->create();
    $resource = Resource::factory()->create();
    $resourceCollection->resources()->sync($resource->id);

    assertDatabaseHas('resources', [
        'id' => $resource->id,
    ]);

    assertDatabaseHas('resource_resource_collection', [
        'resource_collection_id' => $resourceCollection->id,
        'resource_id' => $resource->id,
    ]);

    $resource->delete();

    assertDatabaseMissing('resource_resource_collection', [
        'resource_collection_id' => $resourceCollection->id,
        'resource_id' => $resource->id,
    ]);
});

test('users can view resource collections', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);
    $resourceCollection = ResourceCollection::factory()->create();

    actingAs($user)->get(localized_route('resource-collections.index'))
        ->assertOk()
        ->assertSee($resourceCollection->title);

    actingAs($user)->get(localized_route('resource-collections.show', $resourceCollection))
        ->assertOk()
        ->assertSee($resourceCollection->title)
        ->assertDontSee('Edit resource collection');

    actingAs($administrator)->get(localized_route('resource-collections.show', $resourceCollection))
        ->assertOk()
        ->assertSee('Edit resource collection');
});
