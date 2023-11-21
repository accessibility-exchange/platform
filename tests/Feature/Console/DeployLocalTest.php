<?php

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
    artisan('optimize:clear');
    artisan('icons:clear');
    artisan('event:clear');

    Storage::disk('public')->delete($this->robots);
    Storage::disk('public')->delete($this->sitemap);

    if ($this->disk->fileExists($this->robotsOriginal)) {
        Storage::disk('public')->move($this->robotsOriginal, $this->robots);
    }

    if ($this->disk->fileExists($this->sitemapOriginal)) {
        Storage::disk('public')->move($this->sitemapOriginal, $this->sitemap);
    }
});

test('Completes successfully', function () {
    artisan('deploy:local')->assertSuccessful();
});
