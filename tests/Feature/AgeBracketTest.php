<?php

use App\Models\AgeBracket;
use App\Models\User;
use Database\Seeders\AgeBracketSeeder;
use Spatie\LaravelOptions\Options;

test('only administrators can view age brackets', function () {
    $administrator = User::factory()->create([
        'context' => 'administrator',
    ]);

    $response = $this->actingAs($administrator)->get(localized_route('age-brackets.index'));
    $response->assertOk();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('age-brackets.index'));
    $response->assertForbidden();
});

test('age brackets can be turned into select options', function () {
    $this->seed(AgeBracketSeeder::class);
    expect(Options::forModels(AgeBracket::class)->toArray())->toBeArray()->toHaveCount(AgeBracket::all()->count());
});
