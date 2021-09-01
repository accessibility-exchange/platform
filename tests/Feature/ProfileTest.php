<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Truro',
            'region' => 'NS',
            'creator' => 'self',
            'visibility' => 'all',
        ]);

        $profile = Profile::where('name', $user->name)->get()->first();

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(localized_route('profiles.show', $profile));
    }

    public function test_entity_users_can_not_create_profiles()
    {
        $user = User::factory()->create(['context' => 'entity']);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.create'));
        $response->assertStatus(403);

        $response = $this->from(localized_route('profiles.create'))->post(localized_route('profiles.create'), [
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Truro',
            'region' => 'NS',
            'creator' => 'self',
            'visibility' => 'all',
        ]);

        $response->assertStatus(403);
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
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Truro',
            'region' => 'NS',
            'creator' => 'self',
            'visibility' => 'all',
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_not_create_multiple_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.create'));
        $response->assertStatus(403);

        $response = $this->post(localized_route('profiles.create'), [
            'user_id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hi, welcome to my page.',
            'locality' => 'Truro',
            'region' => 'NS',
            'creator' => 'self',
            'visibility' => 'all',
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_edit_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.edit', $profile));
        $response->assertStatus(200);

        $response = $this->put(localized_route('profiles.update', $profile), [
            'name' => $profile->name,
            'bio' => $profile->bio,
            'locality' => 'St John\'s',
            'region' => 'NL',
            'creator' => $profile->creator,
            'visibility' => $profile->visibility,
        ]);

        $response->assertRedirect(localized_route('profiles.show', $profile));
    }

    public function test_users_can_not_edit_others_profiles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $other_user->id,
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.edit', $profile));
        $response->assertStatus(403);

        $response = $this->put(localized_route('profiles.update', $profile), [
            'name' => $profile->name,
            'bio' => $profile->bio,
            'locality' => 'St John\'s',
            'region' => 'NL',
            'creator' => $profile->creator,
            'visibility' => $profile->visibility,
        ]);
        $response->assertStatus(403);
    }

    public function test_users_can_delete_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('profiles.edit', $profile))->delete(localized_route('profiles.destroy', $profile), [
            'current_password' => 'password',
        ]);
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_can_not_delete_profiles_with_wrong_password()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('profiles.edit', $profile))->delete(localized_route('profiles.destroy', $profile), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('profiles.edit', $profile));
    }

    public function test_users_can_not_delete_others_profiles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $other_user->id,
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('profiles.edit', $profile))->delete(localized_route('profiles.destroy', $profile), [
            'current_password' => 'password',
        ]);
        $response->assertStatus(403);
    }

    public function test_users_can_view_profiles_with_global_visibility()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'visibility' => 'all',
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $other_user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.show', $profile));
        $response->assertStatus(200);
    }

    public function test_users_can_not_view_profiles_with_project_visibility()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'visibility' => 'project',
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $other_user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.show', $profile));
        $response->assertStatus(403);
    }

    public function test_users_can_view_own_draft_profiles()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.show', $profile));
        $response->assertStatus(200);
    }

    public function test_users_can_not_view_others_draft_profiles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        $response = $this->post(localized_route('login'), [
            'email' => $other_user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('profiles.show', $profile));
        $response->assertStatus(403);
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
