<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('users.admin'));

        $response->assertOk();

        $response = $this->from(localized_route('users.admin'))
            ->actingAs($user)
            ->put(localized_route('user-password.update'), [
                'current_password' => 'password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('users.admin'));
    }

    public function test_password_cannot_be_updated_with_incorrect_current_password()
    {
        $user = User::factory()->create();

        $response = $this->from(localized_route('users.admin'))
            ->actingAs($user)
            ->put(localized_route('user-password.update'), [
                'current_password' => 'wrong_password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('users.admin'));
    }

    public function test_password_cannot_be_updated_with_password_that_do_not_match()
    {
        $user = User::factory()->create();

        $response = $this->from(localized_route('users.admin'))
            ->actingAs($user)
            ->put(localized_route('user-password.update'), [
                'current_password' => 'password',
                'password' => 'new_password',
                'password_confirmation' => 'different_new_password',
            ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('users.admin'));
    }

    public function test_password_cannot_be_updated_with_password_that_does_not_meet_requirements()
    {
        $user = User::factory()->create();

        $response = $this->from(localized_route('users.admin'))
            ->actingAs($user)
            ->put(localized_route('user-password.update'), [
                'current_password' => 'password',
                'password' => 'pass',
                'password_confirmation' => 'pass',
            ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('users.admin'));
    }
}
