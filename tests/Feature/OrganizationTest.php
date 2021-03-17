<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\OrganizationInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Str;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_organizations()
    {
        $user = User::factory()->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/en/organizations/create');
        $response->assertStatus(200);

        $response = $this->post('/en/organizations/create', [
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'ns'
        ]);

        $url = '/en/organizations/' . Str::slug($user->name . ' Consulting');

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);
    }

    public function test_users_with_admin_role_can_edit_organizations()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('organizations.edit', $organization));
        $response->assertStatus(200);

        $response = $this->put(localized_route('organizations.update', $organization), [
            'name' => $organization->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertRedirect(localized_route('organizations.show', $organization));
    }

    public function test_users_without_admin_role_can_not_edit_organizations()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('organizations.edit', $organization));
        $response->assertStatus(403);

        $response = $this->put(localized_route('organizations.update', $organization), [
            'name' => $organization->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertStatus(403);
    }

    public function test_non_members_can_not_edit_organizations()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_organization = Organization::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('organizations.edit', $other_organization));
        $response->assertStatus(403);

        $response = $this->put(localized_route('organizations.update', $other_organization), [
            'name' => $other_organization->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertStatus(403);
    }

    public function test_users_with_admin_role_can_update_other_member_roles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this
            ->from(localized_route('organization-user.edit', ['organization' => $organization, 'user' => $other_user]))
            ->put(localized_route('organization-user.update', ['organization' => $organization, 'user' => $user]), [
                'role' => 'admin'
            ]);
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_without_admin_role_can_not_update_member_roles()
    {
        $user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this
            ->from(localized_route('organization-user.edit', ['organization' => $organization, 'user' => $user]))
            ->put(localized_route('organization-user.update', ['organization' => $organization, 'user' => $user]), [
                'role' => 'admin'
            ]);

        $response->assertStatus(403);
    }

    public function test_only_administrator_can_not_downgrade_their_role()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this
            ->from(localized_route('organization-user.edit', ['organization' => $organization, 'user' => $user]))
            ->put(localized_route('organization-user.update', ['organization' => $organization, 'user' => $user]), [
                'role' => 'member'
            ]);

        $response->assertSessionHasErrors(['organization_user']);
        $response->assertRedirect(localized_route('organization-user.edit', ['organization' => $organization, 'user' => $user]));
    }

    public function test_users_with_admin_role_can_invite_members()
    {
        $user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->post(localized_route('organization-invitations.create', ['organization' => $organization]), [
                'email' => 'newuser@here.com',
                'role' => 'member'
            ]);

        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_without_admin_role_can_not_invite_members()
    {
        $user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->post(localized_route('organization-invitations.create', ['organization' => $organization]), [
                'email' => 'newuser@here.com',
                'role' => 'member'
            ]);

        $response->assertStatus(403);
    }

    public function test_existing_members_cannot_be_invited()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->post(localized_route('organization-invitations.create', ['organization' => $organization]), [
                'email' => $other_user->email,
                'role' => 'member'
            ]);

        $response->assertSessionHasErrorsIn('inviteOrganizationMember', ['email']);
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_invitation_can_be_accepted()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $invitation = OrganizationInvitation::factory()->create([
            'email' => $user->email,
            'organization_id' => $organization->id
        ]);

        $acceptUrl = URL::signedRoute('organization-invitations.accept', ['invitation' => $invitation]);

        $response = $this->actingAs($user)->get($acceptUrl);

        $this->assertTrue($organization->fresh()->hasUserWithEmail($user->email));
        $response->assertRedirect(localized_route('organizations.show', $organization));
    }

    public function test_invitation_cannot_be_accepted_unless_account_exists()
    {
        $organization = Organization::factory()->create();
        $invitation = OrganizationInvitation::factory()->create([
            'email' => 'me@here.com',
            'organization_id' => $organization->id
        ]);

        $acceptUrl = URL::signedRoute('organization-invitations.accept', ['invitation' => $invitation]);

        $response = $this->get($acceptUrl);

        $response->assertSessionHasErrors();
    }

    public function test_users_with_admin_role_can_delete_organizations()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
            'current_password' => 'password'
        ]);

        $response->assertRedirect('/en/dashboard');
    }

    public function test_users_with_admin_role_can_not_delete_organizations_with_wrong_password()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
            'current_password' => 'wrong_password'
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_without_admin_role_can_not_delete_organizations()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
            'current_password' => 'password'
        ]);

        $response->assertStatus(403);
    }

    public function test_non_members_can_not_delete_organizations()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_organization = Organization::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('organizations.edit', $other_organization))->delete(localized_route('organizations.destroy', $other_organization), [
            'current_password' => 'password'
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_view_organizations()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/en/organizations');
        $response->assertStatus(200);

        $response = $this->get(localized_route('organizations.show', $organization));
        $response->assertStatus(200);
    }

    public function test_guests_can_not_view_organizations()
    {
        $organization = Organization::factory()->create();

        $response = $this->get('/en/organizations');
        $response->assertRedirect('/en/login');

        $response = $this->get(localized_route('organizations.show', $organization));
        $response->assertRedirect('/en/login');

    }
}
