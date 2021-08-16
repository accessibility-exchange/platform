<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Membership;
use App\Models\Organization;
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
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(localized_route('organizations.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post(localized_route('organizations.create'), [
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'NS',
        ]);

        $url = localized_route('organizations.show', ['organization' => Str::slug($user->name . ' Consulting')]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);
    }

    public function test_users_with_admin_role_can_edit_organizations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('organizations.edit', $organization));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
            'name' => $organization->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertRedirect(localized_route('organizations.show', $organization));
    }

    public function test_users_without_admin_role_can_not_edit_organizations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('organizations.edit', $organization));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(localized_route('organizations.update', $organization), [
            'name' => $organization->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertStatus(403);
    }

    public function test_non_members_can_not_edit_organizations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_organization = Organization::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('organizations.edit', $other_organization));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(localized_route('organizations.update', $other_organization), [
            'name' => $other_organization->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertStatus(403);
    }

    public function test_users_with_admin_role_can_update_other_member_roles()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\Organization')
            ->where('membership_id', $organization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('memberships.edit', $membership))
            ->put(localized_route('memberships.update', $membership), [
                'role' => 'admin',
            ]);
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_without_admin_role_can_not_update_member_roles()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\Organization')
            ->where('membership_id', $organization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('memberships.edit', $membership))
            ->put(localized_route('memberships.update', $membership), [
                'role' => 'admin',
            ]);

        $response->assertStatus(403);
    }

    public function test_only_administrator_can_not_downgrade_their_role()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\Organization')
            ->where('membership_id', $organization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('memberships.edit', $membership))
            ->put(localized_route('memberships.update', $membership), [
                'role' => 'member',
            ]);

        $response->assertSessionHasErrors(['membership']);
        $response->assertRedirect(localized_route('memberships.edit', $membership));
    }

    public function test_users_with_admin_role_can_invite_members()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $organization->id,
                'inviteable_type' => get_class($organization),
                'email' => 'newuser@here.com',
                'role' => 'member',
            ]);

        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_without_admin_role_can_not_invite_members()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $organization->id,
                'inviteable_type' => get_class($organization),
                'email' => 'newuser@here.com',
                'role' => 'member',
            ]);

        $response->assertStatus(403);
    }

    public function test_users_with_admin_role_can_cancel_invitations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $organization->id,
            'inviteable_type' => get_class($organization),
            'email' => 'me@here.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->delete(route('invitations.destroy', ['invitation' => $invitation]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_without_admin_role_can_not_cancel_invitations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $organization->id,
            'inviteable_type' => get_class($organization),
            'email' => 'me@here.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->delete(route('invitations.destroy', ['invitation' => $invitation]));

        $response->assertStatus(403);
    }

    public function test_existing_members_cannot_be_invited()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $organization->id,
                'inviteable_type' => get_class($organization),
                'email' => $other_user->email,
                'role' => 'member',
            ]);

        $response->assertSessionHasErrorsIn('inviteMember', ['email']);
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_invitation_can_be_accepted()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $organization->id,
            'inviteable_type' => get_class($organization),
            'email' => $user->email,
        ]);

        $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

        $response = $this->actingAs($user)->get($acceptUrl);

        $this->assertTrue($organization->fresh()->hasUserWithEmail($user->email));
        $response->assertRedirect(localized_route('organizations.show', $organization));
    }

    public function test_invitation_cannot_be_accepted_by_different_user()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $organization->id,
            'inviteable_type' => get_class($organization),
            'email' => $user->email,
        ]);

        $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

        $response = $this->from(localized_route('dashboard'))->actingAs($other_user)->get($acceptUrl);

        $this->assertFalse($organization->fresh()->hasUserWithEmail($user->email));
        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_with_admin_role_can_remove_members()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\Organization')
            ->where('membership_id', $organization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_without_admin_role_can_not_remove_members()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\Organization')
            ->where('membership_id', $organization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertStatus(403);
    }

    public function test_only_administrator_can_not_remove_themself()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\Organization')
            ->where('membership_id', $organization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('organizations.edit', ['organization' => $organization]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_with_admin_role_can_delete_organizations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_with_admin_role_can_not_delete_organizations_with_wrong_password()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('organizations.edit', $organization));
    }

    public function test_users_without_admin_role_can_not_delete_organizations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
            'current_password' => 'password',
        ]);

        $response->assertStatus(403);
    }

    public function test_non_members_can_not_delete_organizations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $organization = Organization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_organization = Organization::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('organizations.edit', $other_organization))->delete(localized_route('organizations.destroy', $other_organization), [
            'current_password' => 'password',
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_view_organizations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $response = $this->post(localized_route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('organizations.index'));
        $response->assertStatus(200);

        $response = $this->get(localized_route('organizations.show', $organization));
        $response->assertStatus(200);
    }

    public function test_guests_can_not_view_organizations()
    {
        if (! config('hearth.organizations.enabled')) {
            return $this->markTestSkipped('Organization support is not enabled.');
        }

        $organization = Organization::factory()->create();

        $response = $this->get(localized_route('organizations.index'));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('organizations.show', $organization));
        $response->assertRedirect(localized_route('login'));
    }
}
