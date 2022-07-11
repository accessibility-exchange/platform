<?php

use App\Models\IndigenousIdentity;
use App\Models\User;
use Database\Seeders\IndigenousIdentitySeeder;
use Spatie\LaravelOptions\Options;

test('only administrators can view indigenous identities', function () {
    $administrator = User::factory()->create([
        'context' => 'administrator',
    ]);

    $response = $this->actingAs($administrator)->get(localized_route('indigenous-identities.index'));
    $response->assertOk();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('indigenous-identities.index'));
    $response->assertForbidden();
});

test('area types can be turned into select options', function () {
    $this->seed(IndigenousIdentitySeeder::class);
    expect(Options::forModels(IndigenousIdentity::class)->toArray())->toBeArray()->toHaveCount(IndigenousIdentity::all()->count());
});
