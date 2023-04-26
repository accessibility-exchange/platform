<?php

use App\Models\Interpretation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('new Interpretation instance', function () {
    $user = User::factory()->create();

    app()->setLocale('asl');

    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'id="'.Str::slug('The Accessibility Exchange'),
    ];

    $response->assertSeeInOrder($toSee, false);

    $interpretation = Interpretation::firstWhere('name', 'The Accessibility Exchange');

    expect($interpretation)->toBeInstanceOf(Interpretation::class);
    expect($interpretation->route)->toBe('welcome');
    expect($interpretation->namespace)->toBe('welcome');
    expect($interpretation->getTranslation('video', 'asl'))->toBe('');
    expect($interpretation->getTranslation('video', 'lsq'))->toBe('');
});

test('existing Interpretation instance', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
    ]);

    $user = User::factory()->create();
    app()->setLocale('asl');
    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $toSee = [
        '<h1 itemprop="name">',
        'The Accessibility Exchange',
        '</h1>',
        'id="'.Str::slug('The Accessibility Exchange'),
        $interpretation->getTranslation('video', 'asl'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee($interpretation->getTranslation('video', 'lsq'));

    $interpretations = Interpretation::where('name', 'The Accessibility Exchange')->get();

    expect($interpretations)->toHaveCount(1);
    expect($interpretations->first())->toBeInstanceOf(Interpretation::class);
    expect($interpretations->first()->id)->toBe($interpretation->id);
    expect($interpretations->first()->route)->toBe('welcome');
    expect($interpretations->first()->namespace)->toBe('welcome');
    expect($interpretations->first()->getTranslation('video', 'asl'))->toBe($interpretation->getTranslation('video', 'asl'));
    expect($interpretations->first()->getTranslation('video', 'lsq'))->toBe($interpretation->getTranslation('video', 'lsq'));
});

test('Interpretation instance using namespace', function () {
    app()->setLocale('asl');
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

    app()->setLocale('lsq');

    $user = User::factory()->create([
        'locale' => 'lsq',
    ]);
    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $localizedName = __($interpretation->name, [], 'lsq');

    $toSee = [
        '<h1 itemprop="name">',
        $localizedName,
        '</h1>',
        'id="'.Str::slug($localizedName),
        $interpretation->getTranslation('video', 'lsq'),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee($interpretation->getTranslation('video', 'asl'));
});

test('do not fallback to ASL (asl)', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
        'video' => [
            'asl' => 'https://vimeo.com/766454375',
        ],
    ]);

    app()->setLocale('lsq');

    $user = User::factory()->create([
        'locale' => 'lsq',
    ]);
    $response = $this->actingAs($user)->get(localized_route('welcome'));
    $response->assertStatus(200);

    $localizedName = __($interpretation->name, [], 'lsq');

    $toSee = [
        '<h1 itemprop="name">',
        $localizedName,
        '</h1>',
        'id="'.Str::slug($localizedName),
    ];

    $response->assertSeeInOrder($toSee, false);
    $response->assertDontSee($interpretation->getTranslation('video', 'asl'));
});

test('do not fallback to LSQ (lsq)', function () {
    $interpretation = Interpretation::factory()->create([
        'name' => 'The Accessibility Exchange',
        'video' => [
            'lsq' => 'https://vimeo.com/766455246',
        ],
    ]);

    app()->setLocale('asl');

    $user = User::factory()->create([
        'locale' => 'asl',
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
    $response->assertDontSee($interpretation->getTranslation('video', 'lsq'));
});

test('no Interpretation without sign language translations setting enabled', function () {
    $user = User::factory()->create();
    $response = $this->get(localized_route('welcome'));
    $response->assertStatus(200);

    $interpretations = Interpretation::all();

    expect($interpretations)->toHaveCount(0);
});
