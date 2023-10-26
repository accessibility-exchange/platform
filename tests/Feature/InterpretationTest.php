<?php

use App\Filament\Resources\InterpretationResource;
use App\Models\Interpretation;
use App\Models\User;

use function Pest\Livewire\livewire;

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

test('only administrative users can access interpretation admin pages', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $this->actingAs($user)->get(InterpretationResource::getUrl('index'))->assertForbidden();
    $this->actingAs($administrator)->get(InterpretationResource::getUrl('index'))->assertSuccessful();

    $this->actingAs($user)->get(InterpretationResource::getUrl('create'))->assertForbidden();
    $this->actingAs($administrator)->get(InterpretationResource::getUrl('create'))->assertForbidden();

    $this->actingAs($user)->get(InterpretationResource::getUrl('edit', [
        'record' => Interpretation::factory()->create(),
    ]))->assertForbidden();

    $this->actingAs($administrator)->get(InterpretationResource::getUrl('edit', [
        'record' => Interpretation::factory()->create(),
    ]))->assertSuccessful();
});

test('interpretations can be listed', function () {
    $interpretationsWithVideos = Interpretation::factory()->count(2)->create();
    $interpretationsWithoutVideos = Interpretation::factory()->count(2)->create(['video' => ['lsq' => '', 'asl' => '']]);

    livewire(InterpretationResource\Pages\ListInterpretations::class)
        ->assertCanSeeTableRecords($interpretationsWithVideos);

    livewire(InterpretationResource\Pages\ListInterpretations::class)
        ->assertCanSeeTableRecords($interpretationsWithoutVideos);
});
