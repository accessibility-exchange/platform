<?php

use App\Models\Constituency;
use Database\Seeders\ConstituencySeeder;
use Spatie\LaravelOptions\Options;

test('constituencies can be turned into select options', function () {
    $this->seed(ConstituencySeeder::class);
    expect(Options::forModels(Constituency::class)->toArray())->toBeArray()->toHaveCount(Constituency::all()->count());
});
