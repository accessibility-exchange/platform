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
});

afterEach(function () {
    Storage::disk('public')->delete($this->sitemap);

    if ($this->disk->fileExists($this->original)) {
        Storage::disk('public')->move($this->original, $this->sitemap);
    }
});

test('Generate sitemap', function () {
    $this->disk->assertMissing($this->sitemap);

    artisan('seo:generate-sitemap')->assertSuccessful();

    $this->disk->assertExists($this->sitemap);
});

test('Generate sitemaps completes even if file already exists', function () {
    Storage::disk('public')->put($this->sitemap, '<test></test>');

    $this->disk->assertExists($this->sitemap);

    artisan('seo:generate')->assertSuccessful();

    $this->disk->assertExists($this->sitemap);
});
