<?php

use App\Filament\Resources\InterpretationResource;
use App\Models\Interpretation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

test('namespace generated from route', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
    ]);

    expect($interpretation->namespace)->toBe('welcome');
});

test('explicit namespace set', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
        'namespace' => 'reuse',
    ]);

    expect($interpretation->namespace)->toBe('reuse');
});

test('update namespace', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
    ]);

    expect($interpretation->namespace)->toBe('welcome');

    $interpretation->namespace = 'test';
    $interpretation->save();
    $interpretation->refresh();
    expect($interpretation->namespace)->toBe('test');

    $interpretation->namespace = null;
    $interpretation->save();
    $interpretation->refresh();
    expect($interpretation->namespace)->toBe('welcome');
});

test('returns name localized', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
    ]);

    app()->setLocale('fr');
    expect($interpretation->name)->toBe(__('The Accessibility Exchange'));

    app()->setLocale('en');
    expect($interpretation->name)->toBe(__('The Accessibility Exchange'));
});

test('localized video source', function () {
    $videoSrc = [
        'ase' => 'https://vimeo.com/766454375',
        'fcs' => 'https://vimeo.com/766455246',
    ];

    $interpretation = Interpretation::factory()->create([
        'video' => null,
    ]);

    $interpretation->setTranslation('video', 'ase', $videoSrc['ase']);
    $interpretation->setTranslation('video', 'fcs', $videoSrc['fcs']);

    expect($interpretation->getTranslation('video', 'ase'))->toBe($videoSrc['ase']);
    expect($interpretation->getTranslation('video', 'fcs'))->toBe($videoSrc['fcs']);
});

test('get context URL', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
        'route_has_params' => true,
    ]);

    expect($interpretation->getContextURL())->toBeNull();

    $interpretation->route_has_params = false;

    app()->setLocale('fr');
    expect($interpretation->getContextURL())->toBe(localized_route('welcome').'#'.Str::slug($interpretation->name));
    expect($interpretation->getContextURL('en'))->toBe(localized_route('welcome', [], 'en').'#'.Str::slug(__('The Accessibility Exchange', [], 'en')));

    app()->setLocale('en');
    expect($interpretation->getContextURL())->toBe(localized_route('welcome').'#'.Str::slug($interpretation->name));
    expect($interpretation->getContextURL('fr'))->toBe(localized_route('welcome', [], 'fr').'#'.Str::slug(__('The Accessibility Exchange', [], 'fr')));
});

test('only administrative users can access interpretations admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $this->actingAs($user)->get(InterpretationResource::getUrl('index'))->assertForbidden();
    $this->actingAs($administrator)->get(InterpretationResource::getUrl('index'))->assertSuccessful();

    $this->actingAs($user)->get(InterpretationResource::getUrl('create'))->assertForbidden();
    $this->actingAs($administrator)->get(InterpretationResource::getUrl('create'))->assertSuccessful();
});

test('interpretations can be listed', function () {
    $interpretations = Interpretation::factory()->count(10)->create();

    livewire(InterpretationResource\Pages\ListInterpretations::class)
        ->assertCanSeeTableRecords($interpretations);
});
