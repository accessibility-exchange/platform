<?php

use App\Models\User;

test('only administrators can access estimates and agreements admin page', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $response = $this->actingAs($user)->get(localized_route('admin.estimates-and-agreements'));
    $response->assertRedirect(localized_route('dashboard'));

    $response = $this->actingAs($administrator)->get(localized_route('admin.estimates-and-agreements'));
    $response->assertOk();
});
