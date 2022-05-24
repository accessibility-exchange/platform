<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_resources()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

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
    }

    public function test_users_can_edit_resources_belonging_to_them()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(localized_route('resources.edit', $resource));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('resources.update', $resource), [
            'title' => $resource->title,
            'summary' => 'This is my updated resource.',
        ]);
        $response->assertRedirect(localized_route('resources.show', $resource));
    }

    public function test_resources_can_be_translated()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

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
    }

    public function test_users_can_not_edit_resources_belonging_to_others()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('resources.edit', $resource));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('resources.update', $resource), [
            'title' => $resource->title,
            'summary' => 'This is my updated resource.',
        ]);
        $response->assertForbidden();
    }

    public function test_users_can_delete_resources_belonging_to_them()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_can_not_delete_resources_belonging_to_them_with_wrong_password()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('resources.edit', $resource));
    }

    public function test_users_can_not_delete_resources_belonging_to_others()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->from(localized_route('resources.edit', $resource))->delete(localized_route('resources.destroy', $resource), [
            'current_password' => 'password',
        ]);

        $response->assertForbidden();
    }

    public function test_single_resource_can_be_in_many_resource_collections()
    {
        $resource = Resource::factory()->create();

        $resourceCollections = ResourceCollection::factory(3)->create();

        foreach ($resourceCollections as $resourceCollection) {
            $resource->resourceCollections()->sync($resourceCollection->id);
            $this->assertDatabaseHas('resource_resource_collection', [
                'resource_collection_id' => $resourceCollection->id,
                'resource_id' => $resource->id,
            ]);
        }
    }

    public function test_deleting_resource_collection_with_resource()
    {
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
    }
}
