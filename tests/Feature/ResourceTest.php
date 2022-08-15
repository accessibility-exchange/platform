<?php

use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

test('users can create resources', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('resources.create'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('resources.create'), [
        'user_id' => $user->id,
        'title' => 'Test resource',
        'summary' => 'This is my resource.',
    ]);

    $resource = Resource::where('title->en', 'Test resource')->first();

    $url = localized_route('resources.show', $resource);

    $response->assertSessionHasNoErrors();

    $response->assertRedirect($url);

    expect($user->resources)->toHaveCount(1);
});

test('users can edit resources belonging to them', function () {
    $user = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(localized_route('resources.edit', $resource));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('resources.update', $resource), [
        'title' => $resource->title,
        'summary' => 'This is my updated resource.',
    ]);
    $response->assertRedirect(localized_route('resources.show', $resource));
});

test('resources can be translated', function () {
    $resource = Resource::factory()->create();

    $resource->setTranslation('title', 'en', 'title in English');
    $resource->setTranslation('title', 'fr', 'title in French');

    $this->assertEquals('title in English', $resource->title);
    App::setLocale('fr');
    $this->assertEquals('title in French', $resource->title);

    $this->assertEquals('title in English', $resource->getTranslation('title', 'en'));
    $this->assertEquals('title in French', $resource->getTranslation('title', 'fr'));

    $translations = ['en' => 'title in English', 'fr' => 'title in French'];

    $this->assertEquals($translations, $resource->getTranslations('title'));

    $this->expectException(AttributeIsNotTranslatable::class);
    $resource->setTranslation('user_id', 'en', 'user_id in English');
});

test('users can not edit resources belonging to others', function () {
    $user = User::factory()->create();
    $resource = Resource::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('resources.edit', $resource));
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('resources.update', $resource), [
        'title' => $resource->title,
        'summary' => 'This is my updated resource.',
    ]);
    $response->assertForbidden();
});

test('users can delete resources belonging to them', function () {
    $user = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('dashboard'));
});

test('users can not delete resources belonging to them with wrong password', function () {
    $user = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('resources.edit', $resource));
});

test('users can not delete resources belonging to others', function () {
    $user = User::factory()->create();
    $resource = Resource::factory()->create();

    $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('single resource can be in many resource collections', function () {
    $resource = Resource::factory()->create();

    $resourceCollections = ResourceCollection::factory(3)->create();

    foreach ($resourceCollections as $resourceCollection) {
        $resource->resourceCollections()->sync($resourceCollection->id);
        $this->assertDatabaseHas('resource_resource_collection', [
            'resource_collection_id' => $resourceCollection->id,
            'resource_id' => $resource->id,
        ]);
    }
});

test('deleting resource collection with resource', function () {
    $resource = Resource::factory()->create();
    $resourceCollection = ResourceCollection::factory()->create();
    $resource->resourceCollections()->sync($resourceCollection->id);

    $this->assertDatabaseHas('resource_collections', [
        'id' => $resourceCollection->id,
    ]);

    $this->assertDatabaseHas('resource_resource_collection', [
        'resource_collection_id' => $resourceCollection->id,
        'resource_id' => $resource->id,
    ]);

    $resourceCollection->delete();

    $this->assertDatabaseMissing('resource_resource_collection', [
        'resource_collection_id' => $resourceCollection->id,
        'resource_id' => $resource->id,
    ]);
});

test('resources have slugs in both languages even if only one is provided', function () {
    $resource = Resource::factory()->create();
    expect($resource->getTranslation('slug', 'fr', false))
        ->toEqual($resource->getTranslation('slug', 'en', false));

    $resource = Resource::factory()->create(['title' => ['fr' => 'Mon ressource']]);
    expect($resource->getTranslation('slug', 'en', false))
        ->toEqual($resource->getTranslation('slug', 'fr', false));
});
