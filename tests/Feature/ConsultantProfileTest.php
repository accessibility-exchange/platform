<?php

namespace Tests\Feature;

use App\Models\ConsultantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ConsultantProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_consultant_profiles()
    {
        $user = User::factory()->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/en/consultants/create');
        $response->assertStatus(200);

        $response = $this->post('/en/consultants/create', [
            'user_id' => $user->id,
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'ns'
        ]);

        $url = '/en/consultants/' . Str::slug($user->name . ' Consulting');

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);
    }

    public function test_users_can_not_create_consultant_profiles_for_other_users()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/en/consultants/create');
        $response->assertStatus(200);

        $response = $this->post('/en/consultants/create', [
            'user_id' => $other_user->id,
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'ns'
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_not_create_multiple_consultant_profiles()
    {
        $user = User::factory()->create();
        $consultantProfile = ConsultantProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/en/consultants/create');
        $response->assertStatus(403);

        $response = $this->post('/en/consultants/create', [
            'user_id' => $user->id,
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'ns'
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_edit_consultant_profiles()
    {
        $user = User::factory()->create();
        $consultantProfile = ConsultantProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultant-profiles.edit', ['consultantProfile' => $consultantProfile]));
        $response->assertStatus(200);

        $response = $this->put(localized_route('consultant-profiles.update', ['consultantProfile' => $consultantProfile]), [
            'name' => $consultantProfile->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertRedirect(localized_route('consultant-profiles.show', ['consultantProfile' => $consultantProfile]));
    }

    public function test_users_can_not_edit_others_consultant_profiles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $consultantProfile = ConsultantProfile::factory()->create([
            'user_id' => $other_user->id
        ]);

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('consultant-profiles.edit', ['consultantProfile' => $consultantProfile]));
        $response->assertStatus(403);

        $response = $this->put(localized_route('consultant-profiles.update', ['consultantProfile' => $consultantProfile]), [
            'name' => $consultantProfile->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertStatus(403);
    }

    public function test_users_can_delete_consultant_profiles()
    {
        $user = User::factory()->create();
        $consultantProfile = ConsultantProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('consultant-profiles.edit', ['consultantProfile' => $consultantProfile]))->delete(localized_route('consultant-profiles.destroy', ['consultantProfile' => $consultantProfile]), [
            'current_password' => 'password'
        ]);
        $response->assertRedirect('/en/dashboard');
    }

    public function test_users_can_not_delete_consultant_profiles_with_wrong_password()
    {
        $user = User::factory()->create();
        $consultantProfile = ConsultantProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('consultant-profiles.edit', ['consultantProfile' => $consultantProfile]))->delete(localized_route('consultant-profiles.destroy', ['consultantProfile' => $consultantProfile]), [
            'current_password' => 'wrong_password'
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('consultant-profiles.edit', ['consultantProfile' => $consultantProfile]));

    }

    public function test_users_can_not_delete_others_consultant_profiles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $consultantProfile = ConsultantProfile::factory()->create([
            'user_id' => $other_user->id
        ]);

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('consultant-profiles.edit', ['consultantProfile' => $consultantProfile]))->delete(localized_route('consultant-profiles.destroy', ['consultantProfile' => $consultantProfile]), [
            'current_password' => 'password'
        ]);
        $response->assertStatus(403);
    }

    public function test_users_can_view_consultant_profiles()
    {
        $user = User::factory()->create();
        $profile = ConsultantProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/en/consultants');
        $response->assertStatus(200);
    }

    public function test_guests_can_not_view_consultant_profiles()
    {
        $response = $this->get('/en/consultants');
        $response->assertRedirect('/en/login');
    }
}
