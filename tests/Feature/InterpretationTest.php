<?php

use App\Models\Interpretation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

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
