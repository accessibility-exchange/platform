<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $this->refreshApplicationWithLocale('en-CA');

        $response = $this->get('/en/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $this->refreshApplicationWithLocale('en-CA');

        $response = $this->post('/en/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'locale' => 'en-CA'
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
