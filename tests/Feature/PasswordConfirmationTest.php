<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $this->refreshApplicationWithLocale('en-CA');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/en/confirm-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $this->refreshApplicationWithLocale('en-CA');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/en/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $this->refreshApplicationWithLocale('en-CA');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/en/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
