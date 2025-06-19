<?php

use App\Enums\UserContext;
use App\Filament\Resources\DocumentResource;
use App\Filament\Resources\DocumentResource\Pages\CreateDocument;
use App\Filament\Resources\DocumentResource\Pages\EditDocument;
use App\Filament\Resources\DocumentResource\Pages\ListDocuments;
use App\Filament\Resources\DocumentResource\RelationManagers\RevisionsRelationManager;
use App\Models\Document;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => UserContext::Administrator->value]);
});

test('only administrative users can access document admin pages', function () {
    $user = User::factory()->create();
    $document = Document::factory()->create();

    actingAs($user)->get(DocumentResource::getUrl('index'))->assertForbidden();

    actingAs($this->admin)->get(DocumentResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(DocumentResource::getUrl('create'))->assertForbidden();

    actingAs($this->admin)->get(DocumentResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(DocumentResource::getUrl('edit', [
        'record' => $document,
    ]))->assertForbidden();

    actingAs($this->admin)->get(DocumentResource::getUrl('edit', [
        'record' => $document,
    ]))->assertSuccessful();

    actingAs($this->admin)->livewire(RevisionsRelationManager::class, [
        'ownerRecord' => $document,
        'pageClass' => EditDocument::class,
    ])->assertSuccessful();
});

test('documents can be listed', function () {
    actingAs($this->admin);

    $documents = Document::factory(5)->create();

    livewire(ListDocuments::class)
        ->assertCanSeeTableRecords($documents);
});

test('rendering create form', function () {
    livewire(CreateDocument::class)
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('description.en')
        ->assertFormFieldExists('description.fr');
});

test('filling create form', function () {
    livewire(CreateDocument::class)
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

    livewire(CreateDocument::class)
        ->fillForm([
            'name.en' => null,
            'name.fr' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name.en' => 'required_without',
            'name.fr' => 'required_without',
        ]);
});

test('rendering edit form', function () {
    $document = Document::factory()->create();

    livewire(EditDocument::class, ['record' => $document->id])
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('description.en')
        ->assertFormFieldExists('description.fr');
});

test('filling edit form', function () {
    $document = Document::factory()->create();

    livewire(EditDocument::class, ['record' => $document->id])
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

    livewire(EditDocument::class, ['record' => $document->id])
        ->fillForm([
            'name.en' => null,
            'name.fr' => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name.en' => 'required_without',
            'name.fr' => 'required_without',
        ]);
});
