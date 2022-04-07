<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Database\Seeders\ContentTypeSeeder;
use Database\Seeders\FormatSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_hub_can_be_accessed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('resource-collections.index'));
        $response->assertOk();
    }

    public function test_resource_collections_can_be_accessed()
    {
        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('resource-collections.show', $resourceCollection));
        $response->assertOk();
    }

    public function test_resource_collection_resources_appear_in_resource_collection()
    {
        $this->seed(ContentTypeSeeder::class);
        $this->seed(FormatSeeder::class);

        $user = User::factory()->create();
        $resourceCollection = ResourceCollection::factory()->create([
            'user_id' => $user->id,
        ]);
        $resource = Resource::factory()->create();
        $resourceCollection->resources()->attach($resource->id);

        $response = $this->actingAs($user)->get(localized_route('resource-collections.show', $resourceCollection));
        $response->assertSee($resource->title);

        $this->assertEquals(count($resource->resourceCollections), 1);
        $this->assertEquals(count($resourceCollection->resources), 1);
    }
}
