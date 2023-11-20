<?php

use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

beforeEach(function () {
    $this->disk = Storage::disk('public');
    $this->robots = 'robots.txt';
    $this->robotsOriginal = 'robots.original.txt';
    $this->sitemap = 'sitemap.xml';
    $this->sitemapOriginal = 'sitemap.original.xml';

    if ($this->disk->fileExists($this->robots)) {
        Storage::disk('public')->move($this->robots, $this->robotsOriginal);
    }

    if ($this->disk->fileExists($this->sitemap)) {
        Storage::disk('public')->move($this->sitemap, $this->sitemapOriginal);
    }
});

afterEach(function () {
    Storage::disk('public')->delete($this->robots);
    Storage::disk('public')->delete($this->sitemap);

    if ($this->disk->fileExists($this->robotsOriginal)) {
        Storage::disk('public')->move($this->robotsOriginal, $this->robots);
    }

    if ($this->disk->fileExists($this->sitemapOriginal)) {
        Storage::disk('public')->move($this->sitemapOriginal, $this->sitemap);
    }
});

test('Generate seo files', function () {
    $this->disk->assertMissing($this->robots);
    $this->disk->assertMissing($this->sitemap);

    artisan('seo:generate')->assertSuccessful();

    $this->disk->assertExists($this->robots);
    $this->disk->assertExists($this->sitemap);
});

test('Generate seo completes even if files already exist', function () {
    Storage::disk('public')->put($this->robots, 'test');
    Storage::disk('public')->put($this->sitemap, '<test></test>');

    $this->disk->assertExists($this->robots);
    $this->disk->assertExists($this->sitemap);

    artisan('seo:generate')->assertSuccessful();

    $this->disk->assertExists($this->robots);
    $this->disk->assertExists($this->sitemap);
});
