<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_delete_their_own_accounts()
    {
        $user = User::factory()->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response = $this->from('/en/account/admin')->delete('/en/account/delete', [
            'current_password' => 'password'
        ]);

        $this->assertGuest();

        $response->assertRedirect(localized_route('welcome'));
    }

    public function test_users_cannot_delete_their_own_accounts_with_incorrect_password()
    {
        $user = User::factory()->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response = $this->from('/en/account/admin')->delete('/en/account/delete', [
            'current_password' => 'wrong_password'
        ]);

        $response->assertRedirect(localized_route('users.admin'));
    }

    public function test_guests_cannot_delete_accounts()
    {
        $user = User::factory()->create();

        $response = $this->delete('/en/account/delete');

        $response->assertRedirect('/en/login');
    }
}
