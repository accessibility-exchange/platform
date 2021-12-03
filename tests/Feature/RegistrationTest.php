<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get(localized_route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register()
    {
        $response = $this->withSession([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'context' => 'community-member',
        ])->post(localized_route('register-store'), [
            'password' => 'password',
            'password_confirmation' => 'password',
            'locale' => 'en',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_new_users_can_not_register_without_valid_context()
    {
        $response = $this->from(localized_route('register'))->withSession([
            'name' => 'Evil User',
            'email' => 'test@example.com',
            'context' => 'superadmin',
        ])->post(localized_route('register-store'), [
            'password' => 'password',
            'password_confirmation' => 'password',
            'locale' => 'en',
        ]);

        $this->assertGuest();
        $response->assertRedirect(localized_route('register'));
    }
}
