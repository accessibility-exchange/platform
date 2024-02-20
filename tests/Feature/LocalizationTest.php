<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\post;

test('user is redirected to preferred locale on login', function () {
    $user = User::factory()->create(['locale' => 'fr']);

    post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(localized_route('dashboard', [], 'fr'));

    assertAuthenticated();
});

test('user is redirected to preferred locale when editing language preferences', function () {
    $user = User::factory()->create(['locale' => 'fr']);

    actingAs($user)->withCookie('locale', 'fr')->get(localized_route('settings.edit-language-preferences'))
        ->assertRedirect(localized_route('settings.edit-language-preferences', [], 'fr'));
});
