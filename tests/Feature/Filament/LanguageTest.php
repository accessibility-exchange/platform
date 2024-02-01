<?php

use App\Filament\Resources\LanguageResource;
use App\Filament\Resources\LanguageResource\Pages\CreateLanguage;
use App\Filament\Resources\LanguageResource\Pages\EditLanguage;
use App\Filament\Resources\LanguageResource\Pages\ListLanguages;
use App\Models\Language;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['context' => 'administrator']);
});

test('only administrative users can access language admin pages', function () {
    $user = User::factory()->create();
    $language = Language::factory()->create();

    actingAs($user)->get(LanguageResource::getUrl('index'))->assertForbidden();
    actingAs($this->admin)->get(LanguageResource::getUrl('index'))->assertSuccessful();

    actingAs($user)->get(LanguageResource::getUrl('create'))->assertForbidden();
    actingAs($this->admin)->get(LanguageResource::getUrl('create'))->assertSuccessful();

    actingAs($user)->get(LanguageResource::getUrl('edit', [
        'record' => $language,
    ]))->assertForbidden();
    actingAs($this->admin)->get(LanguageResource::getUrl('edit', [
        'record' => $language,
    ]))->assertSuccessful();
});

test('languages can be listed', function () {
    actingAs($this->admin);

    $languages = Language::factory(5)->create();

    livewire(ListLanguages::class)
        ->assertCanSeeTableRecords($languages);
});

test('rendering create form', function () {
    livewire(CreateLanguage::class)
        ->assertFormExists()
        ->assertFormFieldExists('code')
        ->assertSee(__('Language code'))
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr');
});

test('filling create form', function () {
    livewire(CreateLanguage::class)
        ->fillForm([
            'code' => 'es',
            'name.en' => 'Spanish',
            'name.fr' => 'Espagnol',
        ])
        ->assertFormSet([
            'name' => [
                'en' => 'Spanish',
                'fr' => 'Espagnol',
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $language = Language::where('code', 'es')->first();

    expect($language->getTranslations('name'))->toHaveCount(4);
    expect($language->getTranslation('name', 'en'))->toBe('Spanish');
    expect($language->getTranslation('name', 'fr'))->toBe('Espagnol');
    expect($language->getTranslation('name', 'asl'))->toBe('Spanish');
    expect($language->getTranslation('name', 'lsq'))->toBe('Espagnol');

    livewire(CreateLanguage::class)
        ->fillForm([
            'code' => null,
            'name.en' => null,
            'name.fr' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'code' => 'required',
            'name.en' => 'required',
            'name.fr' => 'required',
        ]);
});

test('rendering edit form', function () {
    $language = Language::factory()->create();

    livewire(EditLanguage::class, ['record' => $language->id])
        ->assertFormExists()
        ->assertFormFieldExists('name.en')
        ->assertFormFieldExists('name.fr')
        ->assertFormFieldExists('code')
        ->assertFormFieldIsDisabled('code');
});

test('filling edit form', function () {
    $language = Language::factory()->create();

    livewire(EditLanguage::class, ['record' => $language->id])
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

    $language->refresh();

    expect($language->getTranslation('name', 'en'))->toBe('test');
    expect($language->getTranslation('name', 'fr'))->toBe('teste');
    expect($language->getTranslation('name', 'asl'))->toBe('test');
    expect($language->getTranslation('name', 'lsq'))->toBe('teste');

    livewire(EditLanguage::class, ['record' => $language->id])
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
