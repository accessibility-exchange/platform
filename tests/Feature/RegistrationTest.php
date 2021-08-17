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

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post(localized_route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'locale' => 'en',
            'context' => 'consultant',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_new_users_can_not_register_without_valid_context()
    {
        $response = $this->from(localized_route('register'))->post(localized_route('register'), [
            'name' => 'Evil User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'locale' => 'en',
            'context' => 'superadmin',
        ]);

        $this->assertGuest();
        $response->assertRedirect(localized_route('register'));
    }
}
