<?php

use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

beforeEach(function () {
    $this->disk = Storage::disk('public');
    $this->sitemap = 'sitemap.xml';
    $this->original = 'sitemap.original.xml';

    if ($this->disk->fileExists($this->sitemap)) {
        Storage::disk('public')->move($this->sitemap, $this->original);
    }

    Storage::disk('public')->put($this->sitemap, '<test></test>');
});

afterEach(function () {
    Storage::disk('public')->delete($this->sitemap);

    if ($this->disk->fileExists($this->original)) {
        Storage::disk('public')->move($this->original, $this->sitemap);
    }
});

test('Clear sitemap', function () {
    $this->disk->assertExists($this->sitemap);

    artisan('seo:clear-sitemap')->assertSuccessful();

    $this->disk->assertMissing($this->sitemap);
});

test('Clear sitemap completes even if file did not exist', function () {
    Storage::disk('public')->delete($this->sitemap);
    $this->disk->assertMissing($this->sitemap);

    artisan('seo:clear-sitemap')->assertSuccessful();

    $this->disk->assertMissing($this->sitemap);
});
