<?php

use App\Filament\Resources\SectorResource;
use App\Filament\Resources\SectorResource\Pages\CreateSector;
use App\Filament\Resources\SectorResource\Pages\EditSector;
use App\Filament\Resources\SectorResource\Pages\ListSectors;
use App\Models\Sector;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => 'administrator']);
});

test('only administrative users can access sector admin pages', function () {
    $user = User::factory()->create();
    $sector = Sector::factory()->create();

    actingAs($user)->get(SectorResource::getUrl('index'))->assertForbidden();
    actingAs($this->admin)->get(SectorResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(SectorResource::getUrl('create'))->assertForbidden();
    actingAs($this->admin)->get(SectorResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(SectorResource::getUrl('edit', [
        'record' => $sector,
    ]))->assertForbidden();
    actingAs($this->admin)->get(SectorResource::getUrl('edit', [
        'record' => $sector,
    ]))->assertSuccessful();
});

test('sectors can be listed', function () {
    actingAs($this->admin);

    $sectors = Sector::factory(5)->create();

    livewire(ListSectors::class)
        ->assertCanSeeTableRecords($sectors);
});

test('rendering create form', function () {
    livewire(CreateSector::class)
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('description.en')
        ->assertFormFieldExists('description.fr');
});

test('filling create form', function () {
    livewire(CreateSector::class)
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

    livewire(CreateSector::class)
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
    $sector = Sector::factory()->create();

    livewire(EditSector::class, ['record' => $sector->id])
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('description.en')
        ->assertFormFieldExists('description.fr');
});

test('filling edit form', function () {
    $sector = Sector::factory()->create();

    livewire(EditSector::class, ['record' => $sector->id])
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

    livewire(EditSector::class, ['record' => $sector->id])
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
