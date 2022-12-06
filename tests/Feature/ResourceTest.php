<?php

use App\Enums\ConsultationPhase;
use App\Enums\ResourceFormat;
use App\Filament\Resources\ResourceResource;
use App\Filament\Resources\ResourceResource\Pages\ListResources;
use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use Illuminate\Support\Facades\App;
use function Pest\Livewire\livewire;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;

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

test('users can view resources', function () {
    $user = User::factory()->create();
    $resource = Resource::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('resources.index'));
    $response->assertOk();
    $response->assertSee($resource->title);
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
