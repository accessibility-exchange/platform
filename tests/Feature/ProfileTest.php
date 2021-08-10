<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_profiles()
    {
        $user = User::factory()->create();

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.create'));
        $response->assertStatus(200);

        $response = $this->post(localized_route('profiles.create'), [
            'user_id' => $user->id,
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'ns'
        ]);

        $profile = Profile::where('name', $user->name . ' Consulting')->get()->first();

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(localized_route('profiles.show', $profile));
    }

    public function test_users_can_not_create_profiles_for_other_users()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.create'));
        $response->assertStatus(200);

        $response = $this->post(localized_route('profiles.create'), [
            'user_id' => $other_user->id,
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'ns'
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_not_create_multiple_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.create'));
        $response->assertStatus(403);

        $response = $this->post(localized_route('profiles.create'), [
            'user_id' => $user->id,
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'ns'
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_edit_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.edit', $profile));
        $response->assertStatus(200);

        $response = $this->put(localized_route('profiles.update', $profile), [
            'name' => $profile->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertRedirect(localized_route('profiles.show', $profile));
    }

    public function test_users_can_not_edit_others_profiles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $other_user->id
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.edit', $profile));
        $response->assertStatus(403);

        $response = $this->put(localized_route('profiles.update', $profile), [
            'name' => $profile->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertStatus(403);
    }

    public function test_users_can_delete_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('profiles.edit', $profile))->delete(localized_route('profiles.destroy', $profile), [
            'current_password' => 'password'
        ]);
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_can_not_delete_profiles_with_wrong_password()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('profiles.edit', $profile))->delete(localized_route('profiles.destroy', $profile), [
            'current_password' => 'wrong_password'
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('profiles.edit', $profile));

    }

    public function test_users_can_not_delete_others_profiles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $other_user->id
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('profiles.edit', $profile))->delete(localized_route('profiles.destroy', $profile), [
            'current_password' => 'password'
        ]);
        $response->assertStatus(403);
    }

    public function test_users_can_view_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.index'));
        $response->assertStatus(200);
    }

    public function test_guests_can_not_view_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(localized_route('profiles.index'));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('profiles.show', $profile));
        $response->assertRedirect(localized_route('login'));
    }
}
