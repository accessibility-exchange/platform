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

        $response = $this->delete("/en/people/{$user->slug}");

        $this->assertGuest();

        $response->assertRedirect(localized_route('welcome'));
    }

    public function test_guests_cannot_delete_accounts()
    {
        $user = User::factory()->create();

        $response = $this->delete("/en/people/{$user->slug}");

        $response->assertStatus(403);
    }

    public function test_users_cannot_delete_other_users_accounts()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->delete("/en/people/{$other_user->slug}");
        $response->assertStatus(403);
    }
}
