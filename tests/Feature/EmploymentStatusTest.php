<?php

use App\Models\EmploymentStatus;
use App\Models\User;
use Database\Seeders\EmploymentStatusSeeder;
use Spatie\LaravelOptions\Options;

test('only administrators can view employment statuses', function () {
    $administrator = User::factory()->create([
        'context' => 'administrator',
    ]);

    $response = $this->actingAs($administrator)->get(localized_route('employment-statuses.index'));
    $response->assertOk();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('employment-statuses.index'));
    $response->assertForbidden();
});

test('employment statuses can be turned into select options', function () {
    $this->seed(EmploymentStatusSeeder::class);
    expect(Options::forModels(EmploymentStatus::class)->toArray())->toBeArray()->toHaveCount(EmploymentStatus::all()->count());
});
