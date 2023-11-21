<?php

use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

beforeEach(function () {
    $this->disk = Storage::disk('public');
    $this->robots = 'robots.txt';
    $this->original = 'robots.original.txt';

    if ($this->disk->fileExists($this->robots)) {
        Storage::disk('public')->move($this->robots, $this->original);
    }
});

afterEach(function () {
    Storage::disk('public')->delete($this->robots);

    if ($this->disk->fileExists($this->original)) {
        Storage::disk('public')->move($this->original, $this->robots);
    }
});

test('Generate robots.txt', function () {
    $this->disk->assertMissing($this->robots);

    artisan('seo:generate-robots')->assertSuccessful();

    $this->disk->assertExists($this->robots);
});

test('Generate robots completes even if file already exists', function () {
    Storage::disk('public')->put($this->robots, 'test');

    $this->disk->assertExists($this->robots);

    artisan('seo:generate-robots')->assertSuccessful();

    $this->disk->assertExists($this->robots);
});
