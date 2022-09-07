<?php

use App\Http\Livewire\ResourceSelect;
use App\Models\Resource;
use App\Models\ResourceCollection;

test('resource select renders with a new collection', function () {
    $resources = Resource::factory(5)->create();
    $resourceSelect = $this->livewire(ResourceSelect::class, ['resourceCollectionId' => null]);
    $this->assertEquals(count($resourceSelect->availableResources), 5);
    $this->assertEquals(count($resourceSelect->selectedResources), 0);

    $resourceIds = [];
    foreach ($resources as $resource) {
        $resourceIds[] = $resource->id;
    }
    foreach ($resourceSelect->availableResources as $availableResource) {
        $this->assertTrue(in_array($availableResource['id'], $resourceIds, true));
    }
});

test('resource select renders with an existing collection', function () {
    $resourceCollection = ResourceCollection::factory()->create();
    $selectedResources = Resource::factory(5)->create();
    $resourceCollection->resources()->attach($selectedResources);

    $availableResources = Resource::factory(3)->create();
    $resourceSelect = $this->livewire(ResourceSelect::class, ['resourceCollectionId' => $resourceCollection->id]);
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
});

test('resource can be added to collection', function () {
    $resources = Resource::factory(5)->create();
    $resourceSelect = $this->livewire(ResourceSelect::class, ['resourceCollectionId' => null]);
    $sampleAvailableResource = $resourceSelect->availableResources[1];

    $this->assertEquals(count($resourceSelect->availableResources), 5);
    $this->assertEquals(count($resourceSelect->selectedResources), 0);

    $resourceSelect->call('addResource', 1);
    $this->assertTrue($sampleAvailableResource->id == $resourceSelect->selectedResources[0]->id);

    $this->assertEquals(count($resourceSelect->availableResources), 4);
    $this->assertEquals(count($resourceSelect->selectedResources), 1);
});

test('resource can be removed to collection', function () {
    $resourceCollection = ResourceCollection::factory()->create();
    $selectedResources = Resource::factory(5)->create();
    $resourceCollection->resources()->attach($selectedResources);

    $availableResources = Resource::factory(3)->create();
    $resourceSelect = $this->livewire(ResourceSelect::class, ['resourceCollectionId' => $resourceCollection->id]);
    $sampleSelectedResource = $resourceSelect->selectedResources[1];

    $this->assertEquals(count($resourceSelect->availableResources), 3);
    $this->assertEquals(count($resourceSelect->selectedResources), 5);

    $resourceSelect->call('removeResource', 1);
    $this->assertTrue($sampleSelectedResource->id == $resourceSelect->availableResources[3]->id);

    $this->assertEquals(count($resourceSelect->availableResources), 4);
    $this->assertEquals(count($resourceSelect->selectedResources), 4);
});
