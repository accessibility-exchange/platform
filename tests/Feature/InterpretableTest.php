<?php

use App\Models\Interpretation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('new Interpretable instance', function () {
    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        'id="'.Str::slug('The Accessibility Exchange'),
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee('data-vimeo');

    $interpretation = Interpretation::firstWhere('name', 'The Accessibility Exchange');

    expect($interpretation)->toBeInstanceOf(Interpretation::class);
    expect($interpretation->route)->toBe('welcome');
    expect($interpretation->getTranslation('video', 'ase'))->toBe('');
    expect($interpretation->getTranslation('video', 'fcs'))->toBe('');
});

test('existing Interpretable instance', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
    ]);

    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        'id="'.Str::slug('The Accessibility Exchange'),
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'data-vimeo',
        $interpretation->getTranslation('video', 'ase'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee($interpretation->getTranslation('video', 'fcs'));

    $interpretations = Interpretation::where('name', 'The Accessibility Exchange')->get();

    expect($interpretations)->toHaveCount(1);
    expect($interpretations->first())->toBeInstanceOf(Interpretation::class);
    expect($interpretations->first()->id)->toBe($interpretation->id);
    expect($interpretations->first()->route)->toBe('welcome');
    expect($interpretations->first()->getTranslation('video', 'ase'))->toBe($interpretation->getTranslation('video', 'ase'));
    expect($interpretations->first()->getTranslation('video', 'fcs'))->toBe($interpretation->getTranslation('video', 'fcs'));
});

test('in French and LSQ', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
    ]);

    app()->setLocale('fr');

    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        'id="'.Str::slug('Le Connecteur pour l’accessibilité'),
        '<h1 itemprop="name">',
        'Le Connecteur pour l’accessibilité',
        '</h1>',
        'data-vimeo',
        $interpretation->getTranslation('video', 'fcs'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee($interpretation->getTranslation('video', 'ase'));
});
