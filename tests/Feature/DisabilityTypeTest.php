<?php

use App\Models\DisabilityType;
use App\Models\User;
use Database\Seeders\DisabilityTypeSeeder;
use Spatie\LaravelOptions\Options;

test('only administrators can view disability types', function () {
    $administrator = User::factory()->create([
        'context' => 'administrator',
    ]);

    $response = $this->actingAs($administrator)->get(localized_route('disability-types.index'));
    $response->assertOk();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('disability-types.index'));
    $response->assertForbidden();
});

test('area types can be turned into select options', function () {
    $this->seed(DisabilityTypeSeeder::class);
    expect(Options::forModels(DisabilityType::class)->toArray())->toBeArray()->toHaveCount(DisabilityType::all()->count());
});
