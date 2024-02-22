<?php

use App\Filament\Resources\AccessSupportResource;
use App\Filament\Resources\AccessSupportResource\Pages\CreateAccessSupport;
use App\Filament\Resources\AccessSupportResource\Pages\EditAccessSupport;
use App\Filament\Resources\AccessSupportResource\Pages\ListAccessSupports;
use App\Models\AccessSupport;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => 'administrator']);
});

test('only administrative users can access access support admin pages', function () {
    $user = User::factory()->create();
    $accessSupport = AccessSupport::factory()->create();

    actingAs($user)->get(AccessSupportResource::getUrl('index'))->assertForbidden();
    actingAs($this->admin)->get(AccessSupportResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(AccessSupportResource::getUrl('create'))->assertForbidden();
    actingAs($this->admin)->get(AccessSupportResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(AccessSupportResource::getUrl('edit', [
        'record' => $accessSupport,
    ]))->assertForbidden();
    actingAs($this->admin)->get(AccessSupportResource::getUrl('edit', [
        'record' => $accessSupport,
    ]))->assertSuccessful();
});

test('access supports can be listed', function () {
    actingAs($this->admin);

    $accessSupports = AccessSupport::factory(5)->create();

    livewire(ListAccessSupports::class)
        ->assertCanSeeTableRecords($accessSupports);
});

test('rendering create form', function () {
    livewire(CreateAccessSupport::class)
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('description.en')
        ->assertFormFieldExists('description.fr')
        ->assertFormFieldExists('in_person')
        ->assertFormFieldExists('virtual')
        ->assertFormFieldExists('documents')
        ->assertFormFieldExists('anonymizable');
});

test('filling create form', function () {
    livewire(CreateAccessSupport::class)
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

    livewire(CreateAccessSupport::class)
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
    $accessSupport = AccessSupport::factory()->create();

    livewire(EditAccessSupport::class, ['record' => $accessSupport->id])
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('description.en')
        ->assertFormFieldExists('description.fr')
        ->assertFormFieldExists('in_person')
        ->assertFormFieldExists('virtual')
        ->assertFormFieldExists('documents')
        ->assertFormFieldExists('anonymizable');
});

test('filling edit form', function () {
    $accessSupport = AccessSupport::factory()->create();

    livewire(EditAccessSupport::class, ['record' => $accessSupport->id])
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

    livewire(EditAccessSupport::class, ['record' => $accessSupport->id])
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
