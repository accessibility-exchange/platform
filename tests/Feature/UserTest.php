<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_users_can_access_settings()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('users.settings'));
        $response->assertOk();
    }

    public function test_guests_can_not_access_settings()
    {
        $response = $this->get(localized_route('users.settings'));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_users_can_edit_basic_information()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('users.edit'));
        $response->assertOk();
    }

    public function test_guests_can_not_edit_basic_information()
    {
        $response = $this->get(localized_route('users.edit'));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_users_can_edit_roles_and_permissions()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('users.edit_roles_and_permissions'));
        $response->assertOk();
    }

    public function test_guests_can_not_edit_roles_and_permissions()
    {
        $response = $this->get(localized_route('users.edit_roles_and_permissions'));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_users_can_edit_display_preferences()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('users.edit_display_preferences'));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('users.update_display_preferences'), [
            'theme' => 'system',
        ]);

        $response->assertRedirect(localized_route('users.edit_display_preferences'));
    }

    public function test_guests_can_not_edit_display_preferences()
    {
        $response = $this->get(localized_route('users.edit_display_preferences'));
        $response->assertRedirect(localized_route('login'));
    }

    public function test_users_can_edit_notification_preferences()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('users.edit_notification_preferences'));
        $response->assertOk();
    }

    public function test_guests_can_not_edit_notification_preferences()
    {
        $response = $this->get(localized_route('users.edit_notification_preferences'));
        $response->assertRedirect(localized_route('login'));
    }
}
