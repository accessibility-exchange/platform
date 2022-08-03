<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get(localized_route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    User::factory()->create(['email' => 'me@here.com']);

    $response = $this->from(localized_route('register', ['step' => 1]))
        ->post(localized_route('register-languages'), [
            'locale' => 'en',
            'signed_language' => 'ase',
        ]);
    $response->assertRedirect(localized_route('register', ['step' => 2]));
    $response->assertSessionHas('locale', 'en');
    $response->assertSessionHas('signed_language', 'ase');

    $response = $this->from(localized_route('register', ['step' => 2]))
        ->withSession([
            'locale' => 'en',
            'signed_language' => 'ase',
        ])
        ->post(localized_route('register-context'), [
            'context' => 'individual',
        ]);
    $response->assertRedirect(localized_route('register', ['step' => 3]));
    $response->assertSessionHas('context', 'individual');

    $response = $this->from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'signed_language' => 'ase',
            'context' => 'individual',
        ])
        ->post(localized_route('register-details'), [
            'name' => 'Test User',
            'email' => 'me@here.com',
        ]);

    $response->assertSessionHasErrors(['email']);
    $response->assertRedirect(localized_route('register', ['step' => 3]));

    $response = $this->from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'signed_language' => 'ase',
            'context' => 'individual',
        ])
        ->post(localized_route('register-details'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response->assertRedirect(localized_route('register', ['step' => 4]));
    $response->assertSessionHas('name', 'Test User');
    $response->assertSessionHas('email', 'test@example.com');

    $response = $this->withSession([
        'locale' => 'en',
        'signed_language' => 'ase',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => 'individual',
    ])->post(localized_route('register-store'), [
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(localized_route('users.show-introduction'));
});

test('new users can not register without valid context', function () {
    $response = $this->from(localized_route('register', ['step' => 1]))
        ->post(localized_route('register-context'), [
            'context' => 'superadmin',
        ]);
    $response->assertRedirect(localized_route('register', ['step' => 1]));
    $response->assertSessionHasErrors();
});
