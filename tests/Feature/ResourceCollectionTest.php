<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;
use Tests\TestCase;

class ResourceCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_resource_collections()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

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
    }

    public function test_users_can_add_resources_to_their_new_resource_collection_on_create()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resource = Resource::factory(5)->create();

        $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
            'user_id' => $user->id,
            'title' => 'unique title',
            'description' => 'This is my resource collection',
        ]);
        $response->assertSee('resources', $resource);
    }

    public function test_users_can_edit_resource_collections_belonging_to_them()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(localized_route('resource-collections.edit', $resourceCollection));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
            'title' => $resourceCollection->title,
            'description' => 'This is my updated resource collection.',
        ]);
        $response->assertRedirect(localized_route('resource-collections.show', $resourceCollection));
    }

    public function test_users_can_not_edit_resources_belonging_to_others()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('resource-collections.edit', $resourceCollection));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
            'title' => $resourceCollection->title,
            'description' => 'This is my updated resource collection.',
        ]);
        $response->assertForbidden();
    }

    public function test_users_can_delete_resource_collections_belonging_to_them()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->from(localized_route('resource-collections.edit', $resourceCollection))->delete(localized_route('resource-collections.destroy', $resourceCollection), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_can_not_delete_resource_collections_belonging_to_them_with_wrong_password()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->from(localized_route('resource-collections.edit', $resourceCollection))->delete(localized_route('resource-collections.destroy', $resourceCollection), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('resource-collections.edit', $resourceCollection));
    }

    public function test_users_can_not_delete_resource_collections_belonging_to_others()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory()->create();

        $response = $this->actingAs($user)->from(localized_route('resource-collections.edit', $resourceCollection))->delete(localized_route('resource-collections.destroy', $resourceCollection), [
            'current_password' => 'password',
        ]);

        $response->assertForbidden();
    }

    public function test_resource_collections_can_have_unique_title_for_each_locale()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled');
        }

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
    }

    public function test_create_resource_collection_validation()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled');
        }

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(localized_route('resource-collections.create'), [
            'user_id' => $user->id,
            'title' => 'Test resource collection',
        ]);

        $response->assertSessionHasErrors(['description' => 'The description field is required.']);

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

        $response->assertSessionHasErrors(['title' => 'The title field is required.']);

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
    }

    public function test_update_resource_collection_validation()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled');
        }

        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
            'title' => $resourceCollection->title,
        ]);

        $response->assertSessionHasErrors(['description' => 'The description field is required.']);

        $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
            'title' => $resourceCollection->title,
            'description' => 1,
        ]);

        $response->assertSessionHasErrors(['description' => 'The description must be a string.']);

        $response = $this->actingAs($user)->put(localized_route('resource-collections.update', $resourceCollection), [
            'description' => 'This is my updated resource collection.',
        ]);

        $response->assertSessionHasErrors(['title' => 'The title field is required.']);

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
    }

    public function test_resource_collections_can_be_translated()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

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
    }

    public function test_resource_collections_belong_to_user_get_deleted_on_user_delete()
    {
        $resourceCollection = ResourceCollection::factory()->create();

        $resourceCollection->user->delete();
        $this->assertModelMissing($resourceCollection);
    }

    public function test_single_user_can_have_multiple_resource_collections()
    {
        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory(5)
            ->for($user)
            ->create();
        $this->assertCount(5, $user->resourceCollections()->get());
    }

    public function test_many_resources_can_belong_in_single_resource_collection()
    {
        $resourceCollection = ResourceCollection::factory()->create();

        $resources = Resource::factory(3)->create();

        foreach ($resources as $resource) {
            $resourceCollection->resources()->sync($resource->id);
            $this->assertDatabaseHas('resource_resource_collection', [
                'resource_collection_id' => $resourceCollection->id,
                'resource_id' => $resource->id,
            ]);
        }
    }

    public function test_deleting_resource_belong_to_resource_collection()
    {
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
    }
}
