<?php

use App\Models\AccessSupport;
use Database\Seeders\AccessSupportSeeder;
use Spatie\LaravelOptions\Options;

use function Pest\Laravel\seed;

test('access supports can be turned into select options', function () {
    seed(AccessSupportSeeder::class);
    expect(Options::forModels(AccessSupport::class)->toArray())->toBeArray()->toHaveCount(AccessSupport::all()->count());
});
