<?php

use App\Models\Interpretation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('new Interpretation instance', function () {
    $user = User::factory()->create([
        'sign_language_translations' => true,
    ]);
    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'id="'.Str::slug('The Accessibility Exchange'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee('interpretation__video');

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

    $user = User::factory()->create([
        'sign_language_translations' => true,
    ]);
    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'id="'.Str::slug('The Accessibility Exchange'),
        'interpretation__video',
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
    $response = $this->withCookie('sign_language_translations', true)->get(localized_route('welcome'));
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

    $user = User::factory()->create([
        'sign_language_translations' => true,
    ]);
    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'Le Connecteur pour l’accessibilité',
        '</h1>',
        'id="'.Str::slug('Le Connecteur pour l’accessibilité'),
        'interpretation__video',
        $interpretation->getTranslation('video', 'fcs'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee($interpretation->getTranslation('video', 'ase'));
});

test('do not fallback to ASL (ase)', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
        'video' => [
            'ase' => 'https://vimeo.com/766454375',
        ],
    ]);

    app()->setLocale('fr');

    $user = User::factory()->create([
        'sign_language_translations' => true,
    ]);
    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'Le Connecteur pour l’accessibilité',
        '</h1>',
        'id="'.Str::slug('Le Connecteur pour l’accessibilité'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee('interpretation__video');
    $response->assertDontSee($interpretation->getTranslation('video', 'ase'));
});

test('do not fallback to LSQ (fcs)', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
        'video' => [
            'fcs' => 'https://vimeo.com/766455246',
        ],
    ]);

    app()->setLocale('en');

    $user = User::factory()->create([
        'sign_language_translations' => true,
    ]);
    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'id="'.Str::slug('The Accessibility Exchange'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee('interpretation__video');
    $response->assertDontSee($interpretation->getTranslation('video', 'fcs'));
});

test('no Interpretation without sign language translations setting enabled', function () {
    $user = User::factory()->create([
        'sign_language_translations' => false,
    ]);
    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'id="'.Str::slug('The Accessibility Exchange'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee('interpretation__video');

    $interpretations = Interpretation::all();

    expect($interpretations)->toHaveCount(0);
});
