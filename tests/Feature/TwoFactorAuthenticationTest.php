<?php

use App\Models\User;
use Laravel\Fortify\Features;
use PragmaRX\Google2FA\Google2FA;

test('users_must_confirm_password_before_enabling_two_factor_authentication', function () {
    if (! Features::enabled(Features::twoFactorAuthentication())) {
        return $this->markTestSkipped('Two-factor authentication support is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $response = $this->post(route('two-factor.enable'));

    $response->assertRedirect(localized_route('password.confirm'));

    $this->assertFalse($user->twoFactorAuthEnabled());
});

test('users_who_have_confirmed_password_can_enable_two_factor_authentication', function () {
    if (! Features::enabled(Features::twoFactorAuthentication())) {
        return $this->markTestSkipped('Two-factor authentication support is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post(route('two-factor.enable'));

    $this->assertTrue($user->twoFactorAuthEnabled());
});

test('users_can_authenticate_with_two_factor_code', function () {
    if (! Features::enabled(Features::twoFactorAuthentication())) {
        return $this->markTestSkipped('Two-factor authentication support is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post(route('two-factor.enable'));

    $this->post(localized_route('logout'));

    $this->assertGuest();

    $this->post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->post(localized_route('two-factor.login'), [
        'code' => (new Google2FA())->getCurrentOtp(decrypt($user->two_factor_secret)),
    ]);

    $response->assertRedirect(localized_route('dashboard'));

    $this->assertAuthenticated();
});

test('users_can_not_authenticate_with_invalid_two_factor_code', function () {
    if (! Features::enabled(Features::twoFactorAuthentication())) {
        return $this->markTestSkipped('Two-factor authentication support is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post(route('two-factor.enable'));

    $this->post(localized_route('logout'));

    $this->assertGuest();

    $this->post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->post(localized_route('two-factor.login'), [
        'code' => '123456',
    ]);

    $response->assertRedirect(localized_route('login'));

    $this->assertGuest();
});

test('users_can_authenticate_with_recovery_code', function () {
    if (! Features::enabled(Features::twoFactorAuthentication())) {
        return $this->markTestSkipped('Two-factor authentication support is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post(route('two-factor.enable'));

    $this->post(localized_route('logout'));

    $this->assertGuest();

    $this->post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->post(localized_route('two-factor.login'), [
        'recovery_code' => $user->recoveryCodes()[0],
    ]);

    $response->assertRedirect(localized_route('dashboard'));

    $this->assertAuthenticated();
});

test('users_can_not_authenticate_with_invalid_recovery_code', function () {
    if (! Features::enabled(Features::twoFactorAuthentication())) {
        return $this->markTestSkipped('Two-factor authentication support is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post(route('two-factor.enable'));

    $this->post(localized_route('logout'));

    $this->assertGuest();

    $this->post(localized_route('login-store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->post(localized_route('two-factor.login'), [
        'recovery_code' => 'fake recovery code',
    ]);

    $response->assertRedirect(localized_route('login'));

    $this->assertGuest();
});
