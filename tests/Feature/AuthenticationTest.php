<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->mixedCaseEmailUser = User::factory()->create(['email' => 'John.Smith@example.com']);
});

test('login screen can be rendered', function () {
    get(localized_route('login'))->assertOk();
});

test('users can authenticate using the login screen', function () {
    post(localized_route('login-store'), [
        'email' => $this->user->email,
        'password' => 'password',
    ])
        ->assertRedirect(localized_route('dashboard'));

    assertAuthenticatedAs($this->user);

    Auth::logout();

    post(localized_route('login-store'), [
        'email' => $this->mixedCaseEmailUser->email,
        'password' => 'password',
    ])->assertRedirect(localized_route('dashboard'));

    assertAuthenticatedAs($this->mixedCaseEmailUser);
});

test('users can sign out', function () {
    actingAs($this->user)->post(localized_route('logout'));
    assertGuest();
});

test('users can quickly exit', function () {
    actingAs($this->user)->post(localized_route('exit'))
        ->assertRedirect('https://weather.com');

    assertGuest();
});

test('users can not authenticate with invalid password', function () {
    post(localized_route('login-store'), [
        'email' => $this->user->email,
        'password' => 'wrong-password',
    ]);

    assertGuest();
});
