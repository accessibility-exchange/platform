<?php

use App\Models\ConsultingMethod;
use Database\Seeders\ConsultingMethodSeeder;
use Spatie\LaravelOptions\Options;

test('consulting methods can be turned into select options', function () {
    $this->seed(ConsultingMethodSeeder::class);
    expect(Options::forModels(ConsultingMethod::class)->toArray())->toBeArray()->toHaveCount(ConsultingMethod::all()->count());
});
