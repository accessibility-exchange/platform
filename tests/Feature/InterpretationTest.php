<?php

use App\Models\Interpretation;

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
        'asl' => 'https://vimeo.com/766454375/276fbdc032',
        'lsq' => 'https://vimeo.com/766455246/ccd2109379',
    ];

    $interpretation = Interpretation::factory()->create([
        'video' => null,
    ]);

    $interpretation->setTranslation('video', 'asl', $videoSrc['asl']);
    $interpretation->setTranslation('video', 'lsq', $videoSrc['lsq']);

    expect($interpretation->getTranslation('video', 'asl'))->toBe($videoSrc['asl']);
    expect($interpretation->getTranslation('video', 'lsq'))->toBe($videoSrc['lsq']);
});
