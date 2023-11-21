<?php

use App\Enums\ConsultationPhase;
use App\Enums\ResourceFormat;
use App\Filament\Resources\ResourceResource;
use App\Filament\Resources\ResourceResource\Pages\ListResources;
use App\Models\ContentType;
use App\Models\Impact;
use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\Sector;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\ContentTypeSeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use Database\Seeders\TopicSeeder;
use Illuminate\Support\Facades\App;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

use function Pest\Livewire\livewire;

test('resources can be translated', function () {
    $resource = Resource::factory()->create();

    $resource->setTranslation('title', 'en', 'title in English');
    $resource->setTranslation('title', 'fr', 'title in French');

    expect($resource->title)->toEqual('title in English');
    App::setLocale('fr');
    expect($resource->title)->toEqual('title in French');

    expect($resource->getTranslation('title', 'en'))->toEqual('title in English');
    expect($resource->getTranslation('title', 'fr'))->toEqual('title in French');

    $translations = ['en' => 'title in English', 'fr' => 'title in French'];

    expect($resource->getTranslations('title'))->toEqual($translations);

    $this->expectException(AttributeIsNotTranslatable::class);
    $resource->setTranslation('user_id', 'en', 'user_id in English');
});

test('users can view resources', function () {
    $this->seed(ContentTypeSeeder::class);

    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);
    $resource = Resource::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('resources.index'));
    $response->assertOk();
    $response->assertSee($resource->title);

    $response = $this->actingAs($user)->get(localized_route('resources.show', $resource));
    $response->assertOk();
    $response->assertSee($resource->title);
    $response->assertDontSee('Edit resource');

    $response = $this->actingAs($administrator)->get(localized_route('resources.show', $resource));
    $response->assertOk();
    $response->assertSee('Edit resource');
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

test('resource formats can be displayed', function () {
    $resource = Resource::factory()->create(['formats' => ['pdf']]);
    expect($resource->display_formats)->toContain(ResourceFormat::labels()['pdf']);
});

test('resource phases can be displayed', function () {
    $resource = Resource::factory()->create(['phases' => ['design']]);
    expect($resource->display_phases)->toContain(ConsultationPhase::labels()['design']);

    expect(ConsultationPhase::Design->description())->toEqual('Design your inclusive and accessible consultation');
    expect(ConsultationPhase::Engage->description())->toEqual('Engage with disability and Deaf communities and hold meaningful consultations');
});

test('only administrative users can access resource admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $this->actingAs($user)->get(ResourceResource::getUrl('index'))->assertForbidden();
    $this->actingAs($administrator)->get(ResourceResource::getUrl('index'))->assertSuccessful();

    $this->actingAs($user)->get(ResourceResource::getUrl('create'))->assertForbidden();
    $this->actingAs($administrator)->get(ResourceResource::getUrl('create'))->assertSuccessful();

    $this->actingAs($user)->get(ResourceResource::getUrl('edit', [
        'record' => Resource::factory()->create(),
    ]))->assertForbidden();

    $this->actingAs($administrator)->get(ResourceResource::getUrl('edit', [
        'record' => Resource::factory()->create(),
    ]))->assertSuccessful();
});

test('resources can be listed', function () {
    $resources = Resource::factory()->count(2)->create();

    livewire(ListResources::class)->assertCanSeeTableRecords($resources);
});

test('resources can be scoped by language', function () {
    $englishResource = Resource::factory()->create();
    $frenchResource = Resource::factory()->create(['url' => ['fr' => 'https://example.com/fr']]);

    expect(Resource::all())->toHaveCount(2);

    $englishResources = Resource::whereLanguages(['en'])->pluck('id')->toArray();
    expect($englishResources)->toContain($englishResource->id);
    expect($englishResources)->toHaveCount(1);

    $frenchResources = Resource::whereLanguages(['fr'])->pluck('id')->toArray();
    expect($frenchResources)->toContain($frenchResource->id);
    expect($frenchResources)->toHaveCount(1);
});

test('resources can be scoped by topic', function () {
    $this->seed(TopicSeeder::class);

    $topic = Topic::first();

    $resourceWithTopic = Resource::factory()->create();
    $resourceWithTopic->topics()->attach($topic);
    $resourceWithTopic->refresh();
    $resourceWithoutTopic = Resource::factory()->create();

    $resourcesWithTopic = Resource::whereTopics([$topic->id])->pluck('id')->toArray();
    expect(Resource::all())->toHaveCount(2);
    expect($resourcesWithTopic)->toContain($resourceWithTopic->id);
    expect($resourcesWithTopic)->toHaveCount(1);
});

test('resources can be scoped by phase', function () {
    $designResource = Resource::factory()->create(['phases' => ['design']]);
    $engageResource = Resource::factory()->create(['phases' => ['engage']]);

    expect(Resource::all())->toHaveCount(2);

    $designResources = Resource::wherePhases(['design'])->pluck('id')->toArray();
    expect($designResources)->toContain($designResource->id);
    expect($designResources)->toHaveCount(1);

    $engageResources = Resource::wherePhases(['engage'])->pluck('id')->toArray();
    expect($engageResources)->toContain($engageResource->id);
    expect($engageResources)->toHaveCount(1);
});

test('resources can be scoped by content type', function () {
    $this->seed(ContentTypeSeeder::class);

    $contentType = ContentType::first();

    $resourceWithContentType = Resource::factory()->create();
    $resourceWithContentType->contentType()->associate($contentType->id);
    $resourceWithContentType->save();
    $resourceWithContentType->refresh();

    $resourceWithoutContentType = Resource::factory()->create();

    $resourcesWithContentType = Resource::whereContentTypes([$contentType->id])->pluck('id')->toArray();
    expect(Resource::all())->toHaveCount(2);
    expect($resourcesWithContentType)->toContain($resourceWithContentType->id);
    expect($resourcesWithContentType)->toHaveCount(1);

    expect($contentType->resources->pluck('id')->toArray())->toContain($resourceWithContentType->id);
});

test('resources can be scoped by sector', function () {
    $this->seed(SectorSeeder::class);

    $sector = Sector::first();

    $resourceWithSector = Resource::factory()->create();
    $resourceWithSector->sectors()->attach($sector);
    $resourceWithSector->refresh();
    $resourceWithoutSector = Resource::factory()->create();

    $resourcesWithSector = Resource::whereSectors([$sector->id])->pluck('id')->toArray();
    expect(Resource::all())->toHaveCount(2);
    expect($resourcesWithSector)->toContain($resourceWithSector->id);
    expect($resourcesWithSector)->toHaveCount(1);
});

test('resources can be scoped by area of impact', function () {
    $this->seed(ImpactSeeder::class);

    $impact = Impact::first();

    $resourceWithImpact = Resource::factory()->create();
    $resourceWithImpact->impacts()->attach($impact);
    $resourceWithImpact->refresh();
    $resourceWithoutImpact = Resource::factory()->create();

    $resourcesWithImpact = Resource::whereImpacts([$impact->id])->pluck('id')->toArray();
    expect(Resource::all())->toHaveCount(2);
    expect($resourcesWithImpact)->toContain($resourceWithImpact->id);
    expect($resourcesWithImpact)->toHaveCount(1);
});
