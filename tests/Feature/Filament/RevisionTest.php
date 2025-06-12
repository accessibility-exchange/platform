<?php

use App\Enums\UserContext;
use App\Filament\Resources\RevisionResource;
use App\Filament\Resources\RevisionResource\Pages\ManageRevisions;
use App\Models\Revision;
use App\Models\User;

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

    livewire(ManageRevisions::class)
        ->assertCanSeeTableRecords($revisions);
});
