<?php

test('registration screen can be rendered', function () {
    $response = $this->get(localized_route('register'));

    $response->assertOk();
});

test('new users can register', function () {
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
            'context' => 'community-member',
        ]);
    $response->assertRedirect(localized_route('register', ['step' => 3]));
    $response->assertSessionHas('context', 'community-member');

    $response = $this->from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'signed_language' => 'ase',
            'context' => 'community-member',
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
        'context' => 'community-member',
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
