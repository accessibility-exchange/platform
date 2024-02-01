<?php

use App\Filament\Resources\ImpactResource;
use App\Filament\Resources\ImpactResource\Pages\CreateImpact;
use App\Filament\Resources\ImpactResource\Pages\EditImpact;
use App\Filament\Resources\ImpactResource\Pages\ListImpacts;
use App\Models\Impact;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => 'administrator']);
});

test('only administrative users can access impact admin pages', function () {
    $user = User::factory()->create();
    $impact = Impact::factory()->create();

    actingAs($user)->get(ImpactResource::getUrl('index'))->assertForbidden();
    actingAs($this->admin)->get(ImpactResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(ImpactResource::getUrl('create'))->assertForbidden();
    actingAs($this->admin)->get(ImpactResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(ImpactResource::getUrl('edit', [
        'record' => $impact,
    ]))->assertForbidden();
    actingAs($this->admin)->get(ImpactResource::getUrl('edit', [
        'record' => $impact,
    ]))->assertSuccessful();
});

test('impacts can be listed', function () {
    actingAs($this->admin);

    $impacts = Impact::factory(5)->create();

    livewire(ListImpacts::class)
        ->assertCanSeeTableRecords($impacts);
});

test('rendering create form', function () {
    livewire(CreateImpact::class)
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('description.en')
        ->assertFormFieldExists('description.fr');
});

test('filling create form', function () {
    livewire(CreateImpact::class)
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

    livewire(CreateImpact::class)
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
    $impact = Impact::factory()->create();

    livewire(EditImpact::class, ['record' => $impact->id])
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('description.en')
        ->assertFormFieldExists('description.fr');
});

test('filling edit form', function () {
    $impact = Impact::factory()->create();

    livewire(EditImpact::class, ['record' => $impact->id])
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

    livewire(EditImpact::class, ['record' => $impact->id])
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
