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

test('user is redirected to preferred locale when editing language preferences', function () {
    $user = User::factory()->create(['locale' => 'fr']);

    $response = $this->actingAs($user)->withCookie('locale', 'fr')->get(localized_route('settings.edit-language-preferences'));
    $response->assertRedirect(localized_route('settings.edit-language-preferences', [], 'fr'));
});
