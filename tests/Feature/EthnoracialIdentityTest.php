<?php

use App\Models\EthnoracialIdentity;
use App\Models\User;
use Database\Seeders\EthnoracialIdentitySeeder;
use Spatie\LaravelOptions\Options;

test('only administrators can view ethnoracial identities', function () {
    $administrator = User::factory()->create([
        'context' => 'administrator',
    ]);

    $response = $this->actingAs($administrator)->get(localized_route('ethnoracial-identities.index'));
    $response->assertOk();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('ethnoracial-identities.index'));
    $response->assertForbidden();
});

test('area types can be turned into select options', function () {
    $this->seed(EthnoracialIdentitySeeder::class);
    expect(Options::forModels(EthnoracialIdentity::class)->toArray())->toBeArray()->toHaveCount(EthnoracialIdentity::all()->count());
});
