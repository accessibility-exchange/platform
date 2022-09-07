<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get(localized_route('login'));

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(localized_route('dashboard'));
});

test('users can quickly exit', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(localized_route('exit'));

    $this->assertGuest();
    $response->assertRedirect('https://weather.com');
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});
