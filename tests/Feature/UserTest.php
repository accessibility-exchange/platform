<?php

namespace Tests\Feature;

use App\Models\Entity;
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

        $response = $this->actingAs($user)->put(localized_route('user-profile-information.update'), [
            'name' => 'Jonny Appleseed',
            'email' => $user->email,
            'locale' => $user->locale,
        ]);
        $response->assertRedirect(localized_route('users.edit'));

        $user = $user->fresh();
        $this->assertEquals($user->name, 'Jonny Appleseed');

        $response = $this->actingAs($user)->followingRedirects()->put(localized_route('user-profile-information.update'), [
            'name' => 'Jonny Appleseed',
            'email' => $user->email,
            'locale' => $user->locale,
        ]);
        $response->assertOk();
        $response->assertSee('Your information has been updated.');

        $response = $this->actingAs($user)->followingRedirects()->put(localized_route('user-profile-information.update'), [
            'name' => $user->name,
            'email' => 'me@example.net',
            'locale' => $user->locale,
        ]);
        $response->assertOk();
        $response->assertSee('Please verify your email address by clicking on the link we emailed to you.');

        $user = $user->fresh();
        $this->assertEquals($user->email, 'me@example.net');
        $this->assertNull($user->email_verified_at);
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

    public function test_users_can_access_my_projects_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('users.show_my_projects'));
        $response->assertRedirect(localized_route('dashboard'));

        $entityUser = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($entityUser, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($entityUser)->get(localized_route('users.show_my_projects'));
        $response->assertOk();
    }

    public function test_guests_can_not_access_my_projects_page()
    {
        $response = $this->get(localized_route('users.show_my_projects'));
        $response->assertRedirect(localized_route('login'));
    }
}
