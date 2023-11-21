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

    Storage::disk('public')->put($this->robots, 'test');
});

afterEach(function () {
    Storage::disk('public')->delete($this->robots);

    if ($this->disk->fileExists($this->original)) {
        Storage::disk('public')->move($this->original, $this->robots);
    }
});

test('Clear robots.txt', function () {
    $this->disk->assertExists($this->robots);

    artisan('seo:clear-robots')->assertSuccessful();

    $this->disk->assertMissing($this->robots);
});

test('Clear robots.txt completes even if file did not exist', function () {
    Storage::disk('public')->delete($this->robots);
    $this->disk->assertMissing($this->robots);

    artisan('seo:clear-robots')->assertSuccessful();

    $this->disk->assertMissing($this->robots);
});
