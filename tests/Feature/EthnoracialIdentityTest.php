<?php

use App\Models\User;

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
