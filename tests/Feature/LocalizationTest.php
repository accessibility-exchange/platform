<?php

use App\Models\User;

test('user is redirected to preferred locale on login', function () {
    $user = User::factory()->create(['locale' => 'fr']);

    $response = $this->post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(localized_route('dashboard', [], 'fr'));
});

test('user is redirected to preferred locale when editing profile', function () {
    $user = User::factory()->create(['locale' => 'fr']);

    $response = $this->post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response = $this->withCookie('locale', 'fr')->get(localized_route('users.edit'));
    $response->assertRedirect(localized_route('users.edit', [], 'fr'));
});
