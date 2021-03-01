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

        $response = $this->from('/en/account/edit')->actingAs($user)->put('/en/account/update-password', [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertRedirect('/en/account/edit');
    }

    public function test_password_cannot_be_updated_with_incorrect_current_password()
    {
        $user = User::factory()->create();

        $response = $this->from('/en/account/edit')->actingAs($user)->put('/en/account/update-password', [
            'current_password' => 'wrong_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertRedirect('/en/account/edit');
    }

    public function test_password_cannot_be_updated_with_password_that_do_not_match()
    {
        $user = User::factory()->create();

        $response = $this->from('/en/account/edit')->actingAs($user)->put('/en/account/update-password', [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'different_new_password',
        ]);

        $response->assertRedirect('/en/account/edit');
    }
}
