<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

test('users can create resource collections', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('resource-collections.create'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create', []), [
        'user_id' => $user->id,
        'title' => 'Test resource collection',
        'description' => 'This is my resource collection.',
        'resource_ids' => [],
    ]);

    $resourceCollection = ResourceCollection::where('title->en', 'Test resource collection')->first();

    $url = localized_route('resource-collections.show', $resourceCollection);

    $response->assertSessionHasNoErrors();

    $response->assertRedirect($url);
});

test('users can add resources to their new resource collection on create', function () {
    $user = User::factory()->create();
    $resources = Resource::factory(5)->create();

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'title' => 'unique title',
        'description' => 'This is my resource collection',
        'resource_ids' => $resources->pluck('id')->toArray(),
    ]);

    $response->assertSessionHasNoErrors();

    $resourceCollection = ResourceCollection::where('title->en', 'unique title')->first();

    expect($resourceCollection->resources->count())->toEqual(5);

    $response = $this->actingAs($user)->get(localized_route('resource-collections.show', $resourceCollection));
    foreach ($resources as $resource) {
        $response->assertSee($resource->title);
    }
});

test('users can edit resource collections belonging to them', function () {
    $user = User::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(localized_route('resource-collections.edit', $resourceCollection));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
        'title' => $resourceCollection->title,
        'description' => 'This is my updated resource collection.',
    ]);
    $response->assertRedirect(localized_route('resource-collections.show', $resourceCollection));
});

test('users can not edit resources belonging to others', function () {
    $user = User::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('resource-collections.edit', $resourceCollection));
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
        'title' => $resourceCollection->title,
        'description' => 'This is my updated resource collection.',
    ]);
    $response->assertForbidden();
});

test('users can delete resource collections belonging to them', function () {
    $user = User::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->from(localized_route('resource-collections.edit', $resourceCollection))->delete(localized_route('resource-collections.destroy', $resourceCollection), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('dashboard'));
});

test('users can not delete resource collections belonging to them with wrong password', function () {
    $user = User::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->from(localized_route('resource-collections.edit', $resourceCollection))->delete(localized_route('resource-collections.destroy', $resourceCollection), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('resource-collections.edit', $resourceCollection));
});

test('users can not delete resource collections belonging to others', function () {
    $user = User::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create();

    $response = $this->actingAs($user)->from(localized_route('resource-collections.edit', $resourceCollection))->delete(localized_route('resource-collections.destroy', $resourceCollection), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('resource collections can have unique title for each locale', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'title' => 'unique title',
        'description' => 'This is my resource collection',
    ]);

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'title' => 'unique title',
        'description' => 'This is my resource collection',
    ]);

    $response->assertSessionHasErrors(['title' => 'The title has already been taken.']);

    App::setLocale('fr');

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'title' => 'unique title',
        'description' => 'This is my resource collection',
    ]);

    $response->assertSessionHasNoErrors();
});

test('create resource collection validation', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'title' => 'Test resource collection',
    ]);

    $response->assertSessionHasErrors(['description' => 'You must enter your description.']);

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'title' => 'Test resource collection',
        'description' => 1,
    ]);

    $response->assertSessionHasErrors(['description' => 'The description must be a string.']);

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'description' => 'This is my resource collection',
    ]);

    $response->assertSessionHasErrors(['title' => 'You must enter your title.']);

    $lengthyTitle = '';

    for ($i = 0; $i <= 256; $i++) {
        $lengthyTitle = $lengthyTitle.'a';
    }

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'title' => $lengthyTitle,
        'description' => 'This is my resource collection',
    ]);

    $response->assertSessionHasErrors(['title' => 'The title must not be greater than 255 characters.']);

    $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
        'user_id' => $user->id,
        'title' => 1,
        'description' => 'This is my resource collection',
    ]);

    $response->assertSessionHasErrors(['title' => 'The title must be a string.']);
});

test('update resource collection validation', function () {
    $user = User::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
        'title' => $resourceCollection->title,
    ]);

    $response->assertSessionHasErrors(['description' => 'You must enter your description.']);

    $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
        'title' => $resourceCollection->title,
        'description' => 1,
    ]);

    $response->assertSessionHasErrors(['description' => 'The description must be a string.']);

    $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
        'description' => 'This is my updated resource collection.',
    ]);

    $response->assertSessionHasErrors(['title' => 'You must enter your title.']);

    $lengthyTitle = '';

    for ($i = 0; $i <= 256; $i++) {
        $lengthyTitle = $lengthyTitle.'a';
    }

    $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
        'title' => $lengthyTitle,
        'description' => 'This is my updated resource collection.',
    ]);

    $response->assertSessionHasErrors(['title' => 'The title must not be greater than 255 characters.']);

    $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
        'title' => 1,
        'description' => 'This is my updated resource collection.',
    ]);

    $response->assertSessionHasErrors(['title' => 'The title must be a string.']);
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

    $resources = Resource::factory(3)->create();

    foreach ($resources as $resource) {
        $resourceCollection->resources()->sync($resource->id);
        $this->assertDatabaseHas('resource_resource_collection', [
            'resource_collection_id' => $resourceCollection->id,
            'resource_id' => $resource->id,
        ]);
    }
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
