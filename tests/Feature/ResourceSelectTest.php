<?php

namespace Tests\Feature;

use App\Http\Livewire\ResourceSelect;
use App\Models\Resource;
use App\Models\ResourceCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResourceSelectTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_select_when_resourceCollectionId_parameter_is_null()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $resources = Resource::factory(5)->create();
        $resourceSelect = Livewire::test(ResourceSelect::class, ['resourceCollectionId' => null]);
        $this->assertEquals(count($resourceSelect->availableResources), 5);
        $this->assertEquals(count($resourceSelect->selectedResources), 0);

        $resourceIds = [];
        foreach ($resources as $resource) {
            $resourceIds[] = $resource->id;
        }
        foreach ($resourceSelect->availableResources as $availableResource) {
            $this->assertTrue(in_array($availableResource['id'], $resourceIds, true));
        }
    }

    public function test_resource_select_when_resourceCollectionId_parameter_is_not_null()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $resourceCollection = ResourceCollection::factory()->create();
        $selectedResources = Resource::factory(5)->create();
        $resourceCollection->resources()->attach($selectedResources);

        $availableResources = Resource::factory(3)->create();
        $resourceSelect = Livewire::test(ResourceSelect::class, ['resourceCollectionId' => $resourceCollection->id]);
        $this->assertEquals(count($resourceSelect->availableResources), 3);
        $this->assertEquals(count($resourceSelect->selectedResources), 5);

        $selectedResourceIds = [];
        foreach ($selectedResources as $selectedResource) {
            $selectedResourceIds[] = $selectedResource->id;
        }
        foreach ($resourceSelect->selectedResources as $selectedResource) {
            $this->assertTrue(in_array($selectedResource['id'], $selectedResourceIds, true));
        }

        $availableResourceIds = [];
        foreach ($availableResources as $availableResource) {
            $availableResourceIds[] = $availableResource->id;
        }
        foreach ($resourceSelect->availableResources as $availableResource) {
            $this->assertTrue(in_array($availableResource['id'], $availableResourceIds, true));
        }
    }

    public function test_addResource()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $resources = Resource::factory(5)->create();
        $resourceSelect = Livewire::test(ResourceSelect::class, ['resourceCollectionId' => null]);
        $sampleAvailableResource = $resourceSelect->availableResources[1];

        $this->assertEquals(count($resourceSelect->availableResources), 5);
        $this->assertEquals(count($resourceSelect->selectedResources), 0);

        $resourceSelect->call('addResource', 1);
        $this->assertTrue($sampleAvailableResource->id == $resourceSelect->selectedResources[0]->id);

        $this->assertEquals(count($resourceSelect->availableResources), 4);
        $this->assertEquals(count($resourceSelect->selectedResources), 1);
    }

    public function test_removeResource()
    {
        if (! config('hearth.resources.enabled')) {
            return $this->markTestSkipped('Resource support is not enabled.');
        }

        $resourceCollection = ResourceCollection::factory()->create();
        $selectedResources = Resource::factory(5)->create();
        $resourceCollection->resources()->attach($selectedResources);

        $availableResources = Resource::factory(3)->create();
        $resourceSelect = Livewire::test(ResourceSelect::class, ['resourceCollectionId' => $resourceCollection->id]);
        $sampleSelectedResource = $resourceSelect->selectedResources[1];

        $this->assertEquals(count($resourceSelect->availableResources), 3);
        $this->assertEquals(count($resourceSelect->selectedResources), 5);

        $resourceSelect->call('removeResource', 1);
        $this->assertTrue($sampleSelectedResource->id == $resourceSelect->availableResources[3]->id);

        $this->assertEquals(count($resourceSelect->availableResources), 4);
        $this->assertEquals(count($resourceSelect->selectedResources), 4);
    }
}
