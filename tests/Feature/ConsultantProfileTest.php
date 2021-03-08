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

        $response = $this->get('/en/consultants/' . $consultantProfile->slug . '/edit');
        $response->assertStatus(200);

        $response = $this->put('/en/consultants/' . $consultantProfile->slug . '/edit', [
            'name' => $consultantProfile->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertRedirect('/en/consultants/' . $consultantProfile->slug);
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

        $response = $this->get('/en/consultants/' . $consultantProfile->slug . '/edit');
        $response->assertStatus(403);

        $response = $this->put('/en/consultants/' . $consultantProfile->slug . '/edit', [
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

        $response = $this->from('/en/consultants/' . $consultantProfile->slug . '/edit')->delete('/en/consultants/' . $consultantProfile->slug . '/delete', [
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

        $response = $this->from('/en/consultants/' . $consultantProfile->slug . '/edit')->delete('/en/consultants/' . $consultantProfile->slug . '/delete', [
            'current_password' => 'wrong_password'
        ]);
        ray($response);
        // $response->assertSessionHasErrors();
        $response->assertRedirect('/en/consultants/' . $consultantProfile->slug . '/edit');

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

        $response = $this->from('/en/consultants/' . $consultantProfile->slug . '/edit')->delete('/en/consultants/' . $consultantProfile->slug . '/delete', [
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
