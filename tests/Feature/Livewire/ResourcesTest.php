<?php

use App\Livewire\AllResources;
use App\Livewire\CollectionResources;
use App\Models\ContentType;
use App\Models\Impact;
use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\Sector;
use App\Models\Topic;
use Database\Seeders\ContentTypeSeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use Database\Seeders\TopicSeeder;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->seed(ContentTypeSeeder::class);
    $this->seed(ImpactSeeder::class);
    $this->seed(SectorSeeder::class);
    $this->seed(TopicSeeder::class);

    $this->contentType = ContentType::first();
    $this->impact = Impact::first();
    $this->sector = Sector::first();
    $this->topic = Topic::first();

    $this->resourceCollection = ResourceCollection::factory()->create();
    $this->sampleResource = Resource::factory()->create([
        'title->en' => 'Sample Resource',
        'summary->en' => 'This is an example.',
        'phases' => ['design'],
        'url' => [
            'en' => 'https://example.com',
            'asl' => 'https://example.com/asl',
        ],
    ]);
    $this->sampleResource->contentType()->associate($this->contentType);
    $this->sampleResource->impacts()->attach($this->impact);
    $this->sampleResource->sectors()->attach($this->sector);
    $this->sampleResource->topics()->attach($this->topic);
    $this->sampleResource->save();
    $this->sampleResource->refresh();
    $this->otherResource = Resource::factory()->create(['title->en' => 'Other Resource']);
    $this->resourceCollection->resources()->sync([$this->sampleResource->id, $this->otherResource->id]);
    $this->resourceCollection->refresh();
});

test('test searchQuery property change', function () {
    $allResources = livewire(AllResources::class, ['searchQuery' => ''])
        ->assertSee($this->sampleResource->title)
        ->set('searchQuery', 'Test')
        ->assertDontSee($this->sampleResource->title)
        ->set('searchQuery', 'Sample')
        ->assertSee($this->sampleResource->title)
        ->set('searchQuery', 'sample')
        ->assertSee($this->sampleResource->title)
        ->set('searchQuery', 'EXAMPLE')
        ->assertSee($this->sampleResource->title);

    $collectionResources = livewire(CollectionResources::class, ['resourceCollection' => $this->resourceCollection, 'searchQuery' => ''])
        ->assertSee($this->sampleResource->title)
        ->set('searchQuery', 'Test')
        ->assertDontSee($this->sampleResource->title)
        ->set('searchQuery', 'Sample')
        ->assertSee($this->sampleResource->title)
        ->set('searchQuery', 'sample')
        ->assertSee($this->sampleResource->title)
        ->set('searchQuery', 'EXAMPLE')
        ->assertSee($this->sampleResource->title);
});

test('test sectors property change', function () {
    $allResources = livewire(AllResources::class, ['sectors' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('sectors', [$this->sector->id])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title)
        ->call('selectNone')
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title);

    $collectionResources = livewire(CollectionResources::class, ['resourceCollection' => $this->resourceCollection, 'sectors' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('sectors', [$this->sector->id])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title)
        ->call('selectNone')
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title);
});

test('test impacts property change', function () {
    $allResources = livewire(AllResources::class, ['impacts' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('impacts', [$this->impact->id])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);

    $collectionResources = livewire(CollectionResources::class, ['resourceCollection' => $this->resourceCollection, 'impacts' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('impacts', [$this->impact->id])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);
});

test('test topics property change', function () {
    $allResources = livewire(AllResources::class, ['topics' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('topics', [$this->topic->id])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);

    $collectionResources = livewire(CollectionResources::class, ['resourceCollection' => $this->resourceCollection, 'topics' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('topics', [$this->topic->id])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);
});

test('test contentTypes property change', function () {
    $allResources = livewire(AllResources::class, ['contentTypes' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('contentTypes', [$this->contentType->id])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);

    $collectionResources = livewire(CollectionResources::class, ['resourceCollection' => $this->resourceCollection, 'contentTypes' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('contentTypes', [$this->contentType->id])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);
});

test('test phases property change', function () {
    $allResources = livewire(AllResources::class, ['phases' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('phases', ['design'])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);

    $collectionResources = livewire(CollectionResources::class, ['resourceCollection' => $this->resourceCollection, 'phases' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('phases', ['design'])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);
});

test('test languages property change', function () {
    $allResources = livewire(AllResources::class, ['languages' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('languages', ['asl'])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);

    $collectionResources = livewire(CollectionResources::class, ['resourceCollection' => $this->resourceCollection, 'languages' => []])
        ->assertSee($this->sampleResource->title)
        ->assertSee($this->otherResource->title)
        ->set('languages', ['asl'])
        ->assertSee($this->sampleResource->title)
        ->assertDontSee($this->otherResource->title);
});
