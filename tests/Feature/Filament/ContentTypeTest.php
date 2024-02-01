<?php

use App\Filament\Resources\ContentTypeResource;
use App\Filament\Resources\ContentTypeResource\Pages\CreateContentType;
use App\Filament\Resources\ContentTypeResource\Pages\EditContentType;
use App\Filament\Resources\ContentTypeResource\Pages\ListContentTypes;
use App\Models\ContentType;
use App\Models\Resource;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => 'administrator']);
});

test('only administrative users can access content type admin pages', function () {
    $user = User::factory()->create();
    $contentType = ContentType::factory()->create();

    actingAs($user)->get(ContentTypeResource::getUrl('index'))->assertForbidden();
    actingAs($this->admin)->get(ContentTypeResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(ContentTypeResource::getUrl('create'))->assertForbidden();
    actingAs($this->admin)->get(ContentTypeResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(ContentTypeResource::getUrl('edit', [
        'record' => $contentType,
    ]))->assertForbidden();
    actingAs($this->admin)->get(ContentTypeResource::getUrl('edit', [
        'record' => $contentType,
    ]))->assertSuccessful();
});

test('content types can be listed', function () {
    actingAs($this->admin);

    $contentTypes = ContentType::factory(5)->create();

    livewire(ListContentTypes::class)
        ->assertCanSeeTableRecords($contentTypes);
});

test('rendering create form', function () {
    livewire(CreateContentType::class)
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr');
});

test('filling create form', function () {
    livewire(CreateContentType::class)
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

    livewire(CreateContentType::class)
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
    $contentType = ContentType::factory()
        ->has(Resource::factory(2))
        ->create();

    livewire(EditContentType::class, ['record' => $contentType->id])
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr');

    livewire(ContentTypeResource\RelationManagers\ResourcesRelationManager::class, [
        'ownerRecord' => $contentType,
        'pageClass' => EditContentType::class,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($contentType->resources);
});

test('filling edit form', function () {
    $contentType = ContentType::factory()->create();

    livewire(EditContentType::class, ['record' => $contentType->id])
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

    livewire(EditContentType::class, ['record' => $contentType->id])
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
