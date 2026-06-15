<?php

use App\Enums\UserContext;
use App\Filament\Resources\Documents\Pages\EditDocument;
use App\Filament\Resources\Documents\RelationManagers\RevisionsRelationManager;
use App\Filament\Resources\Revisions\Pages\ManageRevisions;
use App\Filament\Resources\Revisions\RevisionResource;
use App\Models\Document;
use App\Models\Revision;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => UserContext::Administrator->value]);
});

test('only administrative users can access revision admin page', function () {
    $user = User::factory()->create();
    $revision = Revision::factory()->create();

    actingAs($user)->get(RevisionResource::getUrl('index'))->assertForbidden();

    actingAs($this->admin)->get(RevisionResource::getUrl('index'))->assertSuccessful();
});

test('revisions can be listed', function () {
    actingAs($this->admin);

    $revisions = Revision::factory(5)->create();
    $revision = $revisions->first();

    livewire(ManageRevisions::class)
        ->assertCanSeeTableRecords($revisions)
        ->mountAction(TestAction::make('edit')->table($revision))
        ->assertSchemaStateSet([
            'file' => ['en' => [], 'fr' => []],
            'date' => $revision->date->format('Y-m-d'),
        ])
        ->fillForm([
            'file' => ['en' => UploadedFile::fake()->create('test.pdf', 100, 'application/pdf')],
            'date' => fake()->date('Y-m-d'),
        ])
        ->callMountedAction()
        ->assertHasNoFormErrors();
});

test('revisions can be created', function () {
    $document = Document::factory()->create();

    actingAs($this->admin)->livewire(RevisionsRelationManager::class, [
        'ownerRecord' => $document,
        'pageClass' => EditDocument::class,
    ])->callAction(TestAction::make('create')->table(), data: [
        'file' => ['en' => UploadedFile::fake()->create('test.pdf', 100, 'application/pdf')],
    ])
        ->assertHasNoFormErrors();
});
