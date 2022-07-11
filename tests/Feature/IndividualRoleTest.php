<?php

use App\Models\IndividualRole;
use Database\Seeders\IndividualRoleSeeder;
use Spatie\LaravelOptions\Options;

test('consulting methods can be turned into select options', function () {
    $this->seed(IndividualRoleSeeder::class);
    expect(Options::forModels(IndividualRole::class)->toArray())->toBeArray()->toHaveCount(IndividualRole::all()->count());
});
