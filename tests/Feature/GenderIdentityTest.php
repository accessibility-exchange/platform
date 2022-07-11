<?php

use App\Models\GenderIdentity;
use App\Models\User;
use Database\Seeders\GenderIdentitySeeder;
use Spatie\LaravelOptions\Options;

test('only administrators can view gender identities', function () {
    $administrator = User::factory()->create([
        'context' => 'administrator',
    ]);

    $response = $this->actingAs($administrator)->get(localized_route('gender-identities.index'));
    $response->assertOk();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('gender-identities.index'));
    $response->assertForbidden();
});

test('area types can be turned into select options', function () {
    $this->seed(GenderIdentitySeeder::class);
    expect(Options::forModels(GenderIdentity::class)->toArray())->toBeArray()->toHaveCount(GenderIdentity::all()->count());
});
