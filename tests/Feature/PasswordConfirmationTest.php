<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('password.confirm'))
        ->assertOk();
});

test('password can be confirmed', function () {
    $user = User::factory()->create();

    actingAs($user)->post(localized_route('password.confirm'), [
        'password' => 'password',
    ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    actingAs($user)->post(localized_route('password.confirm'), [
        'password' => 'wrong-password',
    ])->assertSessionHasErrors();
});
