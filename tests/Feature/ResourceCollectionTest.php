<?php

use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

test('resource hub can be accessed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('resource-collections.index'));
    $response->assertOk();
});

test('resource collections can be accessed', function () {
    $user = User::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('resource-collections.show', $resourceCollection));
    $response->assertOk();
});

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

    $response = $this->actingAs(User::factory()->create())->get(localized_route('resource-collections.show', $resourceCollection));
    $response->assertOk();
});

test('resource collections belong to user get deleted on user delete', function () {
    $resourceCollection = ResourceCollection::factory()->create();

    $resourceCollection->user->delete();
    $this->assertModelMissing($resourceCollection);
});

test('single user can have multiple resource collections', function () {
    $user = User::factory()->create();
    $resourceCollection = ResourceCollection::factory(5)
        ->for($user)
        ->create();
    $this->assertCount(5, $user->resourceCollections()->get());
});

test('many resources can belong in single resource collection', function () {
    $resourceCollection = ResourceCollection::factory()->create();

    $resources = Resource::factory()->count(3)->create();

    foreach ($resources as $resource) {
        $resourceCollection->resources()->attach($resource->id);
        $this->assertDatabaseHas('resource_resource_collection', [
            'resource_collection_id' => $resourceCollection->id,
            'resource_id' => $resource->id,
        ]);
    }

    foreach ($resources as $resource) {
        $resource = $resource->fresh();
        expect($resource->resourceCollections)->toHaveCount(1);
    }

    expect($resourceCollection->resources)->toHaveCount(3);
});

test('deleting resource belong to resource collection', function () {
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
