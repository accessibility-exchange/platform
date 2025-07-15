<?php

use App\Enums\UserContext;
use App\Filament\Resources\ResourceTypeResource;
use App\Filament\Resources\ResourceTypeResource\Pages\CreateResourceType;
use App\Filament\Resources\ResourceTypeResource\Pages\EditResourceType;
use App\Filament\Resources\ResourceTypeResource\Pages\ListResourceTypes;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => UserContext::Administrator->value]);
});

test('only administrative users can access resource type admin pages', function () {
    $user = User::factory()->create();
    $resourceType = ResourceType::factory()->create();

    actingAs($user)->get(ResourceTypeResource::getUrl('index'))->assertForbidden();
    actingAs($this->admin)->get(ResourceTypeResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(ResourceTypeResource::getUrl('create'))->assertForbidden();
    actingAs($this->admin)->get(ResourceTypeResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(ResourceTypeResource::getUrl('edit', [
        'record' => $resourceType,
    ]))->assertForbidden();
    actingAs($this->admin)->get(ResourceTypeResource::getUrl('edit', [
        'record' => $resourceType,
    ]))->assertSuccessful();
});

test('resource types can be listed', function () {
    actingAs($this->admin);

    $resourceTypes = ResourceType::factory(5)->create();

    livewire(ListResourceTypes::class)
        ->assertCanSeeTableRecords($resourceTypes);
});

test('rendering create form', function () {
    livewire(CreateResourceType::class)
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr');
});

test('filling create form', function () {
    livewire(CreateResourceType::class)
        ->fillForm([
            'name.en' => 'test',
            'name.fr' => 'teste',
        ])
        ->assertFormSet([
            'name' => [
                'en' => 'test',
                'fr' => 'teste',
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    livewire(CreateResourceType::class)
        ->fillForm([
            'name.en' => null,
            'name.fr' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name.en' => 'required',
            'name.fr' => 'required',
        ]);
});

test('rendering edit form', function () {
    $resourceType = ResourceType::factory()
        ->has(Resource::factory(2))
        ->create();

    livewire(EditResourceType::class, ['record' => $resourceType->id])
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr');

    livewire(ResourceTypeResource\RelationManagers\ResourcesRelationManager::class, [
        'ownerRecord' => $resourceType,
        'pageClass' => EditResourceType::class,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($resourceType->resources);
});

test('filling edit form', function () {
    $resourceType = ResourceType::factory()->create();

    livewire(EditResourceType::class, ['record' => $resourceType->id])
        ->fillForm([
            'name.en' => 'test',
            'name.fr' => 'teste',
        ])
        ->assertFormSet([
            'name' => [
                'en' => 'test',
                'fr' => 'teste',
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    livewire(EditResourceType::class, ['record' => $resourceType->id])
        ->fillForm([
            'name.en' => null,
            'name.fr' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name.en' => 'required',
            'name.fr' => 'required',
        ]);
});
