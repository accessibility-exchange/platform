<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_delete_their_own_accounts()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('users.delete'));
        $response->assertOk();

        $response = $this->actingAs($user)->from(localized_route('users.delete'))->delete(localized_route('users.destroy'), [
            'current_password' => 'password',
        ]);

        $this->assertGuest();

        $response->assertRedirect(localized_route('welcome'));
    }

    public function test_users_cannot_delete_their_own_accounts_with_incorrect_password()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from(localized_route('users.admin'))->delete(localized_route('users.destroy'), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertRedirect(localized_route('users.admin'));
    }

    public function test_users_cannot_delete_their_own_accounts_without_assigning_other_admin_to_organization()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->from(localized_route('users.admin'))->delete(localized_route('users.destroy'), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('users.admin'));
    }

    public function test_users_cannot_delete_their_own_accounts_without_assigning_other_admin_to_regulatedOrganization()
    {
        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->from(localized_route('users.admin'))->delete(localized_route('users.destroy'), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('users.admin'));
    }

    public function test_guests_cannot_delete_accounts()
    {
        $user = User::factory()->create();

        $response = $this->delete(localized_route('users.destroy'));

        $response->assertRedirect(localized_route('login'));
    }
}
