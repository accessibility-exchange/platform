<?php

use App\Models\AreaType;
use App\Models\User;
use Database\Seeders\AreaTypeSeeder;
use Spatie\LaravelOptions\Options;

test('only administrators can view area types', function () {
    $administrator = User::factory()->create([
        'context' => 'administrator',
    ]);

    $response = $this->actingAs($administrator)->get(localized_route('area-types.index'));
    $response->assertOk();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('area-types.index'));
    $response->assertForbidden();
});

test('area types can be turned into select options', function () {
    $this->seed(AreaTypeSeeder::class);
    expect(Options::forModels(AreaType::class)->toArray())->toBeArray()->toHaveCount(AreaType::all()->count());
});
