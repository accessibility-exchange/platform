<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Membership;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegulatedOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_regulated_organizations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);

        $response = $this->actingAs($user)->get(localized_route('regulated-organizations.create'));
        $response->assertOk();

        $response = $this->actingAs($user)->post(localized_route('regulated-organizations.store-name'), [
            'name' => $user->name . ' Inc.',
        ]);

        $response->assertRedirect(localized_route('regulated-organizations.create', ['step' => 2]));
        $response->assertSessionHas('name', $user->name . ' Inc.');

        $response = $this->actingAs($user)->withSession(['name' => $user->name . ' Inc.'])->post(localized_route('regulated-organizations.create'), [
            'languages' => ['en', 'fr', 'ase', 'fcs'],
        ]);

        $url = localized_route('regulated-organizations.show', ['regulatedOrganization' => Str::slug($user->name . ' Inc.')]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);

        $regulatedOrganization = RegulatedOrganization::where('name', $user->name . ' Inc.')->first();

        $this->assertTrue($user->isMemberOf($regulatedOrganization));
        $this->assertEquals(1, count($user->memberships));
    }

    public function test_users_primary_entity_can_be_retrieved()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $user = $user->fresh();

        $this->assertEquals($user->regulatedOrganization()->id, $regulatedOrganization->id);
    }

    public function test_users_with_admin_role_can_edit_regulated_organizations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('regulated-organizations.edit', $regulatedOrganization));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
            'name' => $regulatedOrganization->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));
    }

    public function test_users_without_admin_role_can_not_edit_regulated_organizations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('regulated-organizations.edit', $regulatedOrganization));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
            'name' => $regulatedOrganization->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertForbidden();
    }

    public function test_non_members_can_not_edit_regulated_organizations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $other_user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_entity = RegulatedOrganization::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('regulated-organizations.edit', $other_entity));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('regulated-organizations.update', $other_entity), [
            'name' => $other_entity->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertForbidden();
    }

    public function test_users_with_admin_role_can_update_other_member_roles()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $other_user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\RegulatedOrganization')
            ->where('membership_id', $regulatedOrganization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('memberships.edit', $membership))
            ->put(localized_route('memberships.update', $membership), [
                'role' => 'admin',
            ]);
        $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    public function test_users_without_admin_role_can_not_update_member_roles()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\RegulatedOrganization')
            ->where('membership_id', $regulatedOrganization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('memberships.edit', $membership))
            ->put(localized_route('memberships.update', $membership), [
                'role' => 'admin',
            ]);

        $response->assertForbidden();
    }

    public function test_only_administrator_can_not_downgrade_their_role()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $other_user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\RegulatedOrganization')
            ->where('membership_id', $regulatedOrganization->id)
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
        $user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('regulated-organizations.edit', ['regulatedOrganization' => $regulatedOrganization]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $regulatedOrganization->id,
                'inviteable_type' => get_class($regulatedOrganization),
                'email' => 'newuser@here.com',
                'role' => 'member',
            ]);

        $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    public function test_users_without_admin_role_can_not_invite_members()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('regulated-organizations.edit', ['regulatedOrganization' => $regulatedOrganization]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $regulatedOrganization->id,
                'inviteable_type' => get_class($regulatedOrganization),
                'email' => 'newuser@here.com',
                'role' => 'member',
            ]);

        $response->assertForbidden();
    }

    public function test_users_with_admin_role_can_cancel_invitations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $regulatedOrganization->id,
            'inviteable_type' => get_class($regulatedOrganization),
            'email' => 'me@here.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(localized_route('regulated-organizations.edit', ['regulatedOrganization' => $regulatedOrganization]))
            ->delete(route('invitations.destroy', ['invitation' => $invitation]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    public function test_users_without_admin_role_can_not_cancel_invitations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $regulatedOrganization->id,
            'inviteable_type' => get_class($regulatedOrganization),
            'email' => 'me@here.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(localized_route('regulated-organizations.edit', ['regulatedOrganization' => $regulatedOrganization]))
            ->delete(route('invitations.destroy', ['invitation' => $invitation]));

        $response->assertForbidden();
    }

    public function test_existing_members_cannot_be_invited()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $other_user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('regulated-organizations.edit', ['regulatedOrganization' => $regulatedOrganization]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $regulatedOrganization->id,
                'inviteable_type' => get_class($regulatedOrganization),
                'email' => $other_user->email,
                'role' => 'member',
            ]);

        $response->assertSessionHasErrorsIn('inviteMember', ['email']);
        $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    public function test_invitation_can_be_accepted()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $regulatedOrganization = RegulatedOrganization::factory()->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $regulatedOrganization->id,
            'inviteable_type' => get_class($regulatedOrganization),
            'email' => $user->email,
        ]);

        $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

        $response = $this->actingAs($user)->get($acceptUrl);

        $this->assertTrue($regulatedOrganization->fresh()->hasUserWithEmail($user->email));
        $response->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));
    }

    public function test_invitation_cannot_be_accepted_by_different_user()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $other_user = User::factory()->create(['context' => 'regulated-organization']);
        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $regulatedOrganization->id,
            'inviteable_type' => get_class($regulatedOrganization),
            'email' => $user->email,
        ]);

        $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

        $response = $this->from(localized_route('dashboard'))->actingAs($other_user)->get($acceptUrl);

        $this->assertFalse($regulatedOrganization->fresh()->hasUserWithEmail($user->email));
        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_with_admin_role_can_remove_members()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $other_user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\RegulatedOrganization')
            ->where('membership_id', $regulatedOrganization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('regulated-organizations.edit', ['regulatedOrganization' => $regulatedOrganization]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    public function test_users_without_admin_role_can_not_remove_members()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $other_user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\RegulatedOrganization')
            ->where('membership_id', $regulatedOrganization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('regulated-organizations.edit', ['regulatedOrganization' => $regulatedOrganization]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertForbidden();
    }

    public function test_only_administrator_can_not_remove_themself()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\RegulatedOrganization')
            ->where('membership_id', $regulatedOrganization->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('regulated-organizations.edit', ['regulatedOrganization' => $regulatedOrganization]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    public function test_users_with_admin_role_can_delete_regulated_organizations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_with_admin_role_can_not_delete_entities_with_wrong_password()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    }

    public function test_users_without_admin_role_can_not_delete_regulated_organizations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
            'current_password' => 'password',
        ]);

        $response->assertForbidden();
    }

    public function test_non_members_can_not_delete_regulated_organizations()
    {
        $user = User::factory()->create(['context' => 'regulated-organization']);
        $other_user = User::factory()->create(['context' => 'regulated-organization']);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_entity = RegulatedOrganization::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('regulated-organizations.edit', $other_entity))->delete(localized_route('regulated-organizations.destroy', $other_entity), [
            'current_password' => 'password',
        ]);

        $response->assertForbidden();
    }

    public function test_users_can_view_regulated_organizations()
    {
        $user = User::factory()->create();
        $regulatedOrganization = RegulatedOrganization::factory()->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('regulated-organizations.index'));
        $response->assertOk();

        $response = $this->get(localized_route('regulated-organizations.show', $regulatedOrganization));
        $response->assertOk();

        $response = $this->get(localized_route('regulated-organizations.show-accessibility-and-inclusion', $regulatedOrganization));
        $response->assertOk();

        $response = $this->get(localized_route('regulated-organizations.show-projects', $regulatedOrganization));
        $response->assertOk();
    }

    public function test_guests_can_not_view_regulated_organizations()
    {
        $regulatedOrganization = RegulatedOrganization::factory()->create();

        $response = $this->get(localized_route('regulated-organizations.index'));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('regulated-organizations.show', $regulatedOrganization));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('regulated-organizations.show-accessibility-and-inclusion', $regulatedOrganization));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('regulated-organizations.show-projects', $regulatedOrganization));
        $response->assertRedirect(localized_route('login'));
    }
}
