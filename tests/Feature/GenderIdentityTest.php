<?php

use App\Models\User;

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
