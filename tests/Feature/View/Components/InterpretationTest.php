<?php

use App\Models\Interpretation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('new Interpretation instance', function () {
    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'id="'.Str::slug('The Accessibility Exchange'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee('data-vimeo');

    $interpretation = Interpretation::firstWhere('name', 'The Accessibility Exchange');

    expect($interpretation)->toBeInstanceOf(Interpretation::class);
    expect($interpretation->route)->toBe('welcome');
    expect($interpretation->namespace)->toBe('welcome');
    expect($interpretation->getTranslation('video', 'ase'))->toBe('');
    expect($interpretation->getTranslation('video', 'fcs'))->toBe('');
});

test('existing Interpretation instance', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
    ]);

    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'id="'.Str::slug('The Accessibility Exchange'),
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
    expect($interpretations->first()->namespace)->toBe('welcome');
    expect($interpretations->first()->getTranslation('video', 'ase'))->toBe($interpretation->getTranslation('video', 'ase'));
    expect($interpretations->first()->getTranslation('video', 'fcs'))->toBe($interpretation->getTranslation('video', 'fcs'));
});

test('Interpretation instance using namespace', function () {
    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h2 id="join">',
        'Join our accessibility community',
        '</h2>',
        'id="'.Str::slug('Join our accessibility community'),
    ];

    $response->assertSeeInOrder($toSee, false);

    $interpretation = Interpretation::firstWhere('name', 'Join our accessibility community');

    expect($interpretation)->toBeInstanceOf(Interpretation::class);
    expect($interpretation->route)->toBe('welcome');
    expect($interpretation->namespace)->toBe('join');
});

test('in French and LSQ', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
    ]);

    app()->setLocale('fr');

    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'Le Connecteur pour l’accessibilité',
        '</h1>',
        'id="'.Str::slug('Le Connecteur pour l’accessibilité'),
        'data-vimeo',
        $interpretation->getTranslation('video', 'fcs'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee($interpretation->getTranslation('video', 'ase'));
});
