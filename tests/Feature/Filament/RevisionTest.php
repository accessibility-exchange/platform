<?php

use App\Enums\UserContext;
use App\Filament\Resources\DocumentResource\RelationManagers\RevisionsRelationManager;
use App\Filament\Resources\RevisionResource;
use App\Filament\Resources\RevisionResource\Pages\ManageRevisions;
use App\Models\Document;
use App\Models\Revision;
use App\Models\User;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
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

    $file = UploadedFile::fake();

    $revisions = Revision::factory(5)->create();

    livewire(ManageRevisions::class)
        ->assertCanSeeTableRecords($revisions)
        ->mountTableAction(EditAction::class, $revisions->first())
        ->assertTableActionDataSet([
            'file' => ['en' => [], 'fr' => []],
            'date' => $revisions->first()->date->format('Y-m-d'),
        ])
        ->setTableActionData([
            'file' => ['en' => null],
            'date' => fake()->date('Y-m-d'),
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();
});

test('revisions can be created', function () {
    $document = Document::factory()->create();

    actingAs($this->admin)->livewire(RevisionsRelationManager::class, [
        'ownerRecord' => $document,
        'pageClass' => EditDocument::class,
    ])->callTableAction(CreateAction::class)
        ->setTableActionData([
            'file' => ['en' => null],
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();
});
