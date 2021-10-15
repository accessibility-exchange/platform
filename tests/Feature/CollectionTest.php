<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\Resource;
use App\Models\Story;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_hub_can_be_accessed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('collections.index'));
        $response->assertOk();
    }

    public function test_collections_can_be_accessed()
    {
        $user = User::factory()->create();
        $collection = Collection::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(localized_route('collections.show', $collection));
        $response->assertOk();
    }

    public function test_collection_resources_appear_in_collection()
    {
        $user = User::factory()->create();
        $collection = Collection::factory()->create([
            'user_id' => $user->id,
        ]);
        $resource = Resource::factory()->create();
        $collection->resources()->attach($resource->id);

        $response = $this->actingAs($user)->get(localized_route('collections.show', $collection));
        $response->assertSee($resource->title);

        $this->assertEquals(count($resource->collections), 1);
        $this->assertEquals(count($collection->resources), 1);
    }

    public function test_collection_stories_appear_in_collection()
    {
        $user = User::factory()->create();
        $collection = Collection::factory()->create([
            'user_id' => $user->id,
        ]);
        $story = Story::factory()->create();
        $collection->stories()->attach($story->id);

        $this->assertEquals(count($story->collections), 1);
        $this->assertEquals(count($collection->stories), 1);
    }
}
