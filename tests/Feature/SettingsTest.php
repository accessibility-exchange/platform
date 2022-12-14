<?php

use App\Models\User;

test('only administrative users can access the settings page', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $response = $this->actingAs($user)->get(route('filament.pages.settings'));
    $response->assertForbidden();

    $response = $this->actingAs($administrator)->get(route('filament.pages.settings'));
    $response->assertSuccessful();
    $response->assertSeeInOrder(['Support email', 'Support phone', 'Mailing address', 'Facebook page', 'LinkedIn page', 'Twitter page',
        'YouTube page', ]);
});
