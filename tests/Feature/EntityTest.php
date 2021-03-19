<?php

namespace Tests\Feature;

use App\Models\Membership;
use App\Models\Entity;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Str;
use Tests\TestCase;

class EntityTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_entities()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/en/entities/create');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->post('/en/entities/create', [
            'name' => $user->name . ' Consulting',
            'locality' => 'Truro',
            'region' => 'ns'
        ]);

        $url = '/en/entities/' . Str::slug($user->name . ' Consulting');

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);
    }

    public function test_users_with_admin_role_can_edit_entities()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('entities.edit', $entity));
        $response->assertStatus(200);

        $response = $this->actingAs($user)->put(localized_route('entities.update', $entity), [
            'name' => $entity->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertRedirect(localized_route('entities.show', $entity));
    }

    public function test_users_without_admin_role_can_not_edit_entities()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('entities.edit', $entity));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(localized_route('entities.update', $entity), [
            'name' => $entity->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertStatus(403);
    }

    public function test_non_members_can_not_edit_entities()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_entity = Entity::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('entities.edit', $other_entity));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->put(localized_route('entities.update', $other_entity), [
            'name' => $other_entity->name,
            'locality' => 'St John\'s',
            'region' => 'nl'
        ]);
        $response->assertStatus(403);
    }

    public function test_users_with_admin_role_can_update_other_member_roles()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\Entity')
            ->where('membership_id', $entity->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('memberships.edit', $membership))
            ->put(localized_route('memberships.update', $membership), [
                'role' => 'admin'
            ]);
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_without_admin_role_can_not_update_member_roles()
    {
        $user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\Entity')
            ->where('membership_id', $entity->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('memberships.edit', $membership))
            ->put(localized_route('memberships.update', $membership), [
                'role' => 'admin'
            ]);

        $response->assertStatus(403);
    }

    public function test_only_administrator_can_not_downgrade_their_role()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\Entity')
            ->where('membership_id', $entity->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('memberships.edit', $membership))
            ->put(localized_route('memberships.update', $membership), [
                'role' => 'member'
            ]);

        $response->assertSessionHasErrors(['membership']);
        $response->assertRedirect(localized_route('memberships.edit', $membership));
    }

    public function test_users_with_admin_role_can_invite_members()
    {
        $user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $entity->id,
                'inviteable_type' => $entity->getModelClass(),
                'email' => 'newuser@here.com',
                'role' => 'member'
            ]);

        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_without_admin_role_can_not_invite_members()
    {
        $user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $entity->id,
                'inviteable_type' => $entity->getModelClass(),
                'email' => 'newuser@here.com',
                'role' => 'member'
            ]);

        $response->assertStatus(403);
    }

    public function test_users_with_admin_role_can_cancel_invitations()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $entity->id,
            'inviteable_type' => $entity->getModelClass(),
            'email' => 'me@here.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->delete(route('invitations.destroy', ['invitation' => $invitation]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_without_admin_role_can_not_cancel_invitations()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $entity->id,
            'inviteable_type' => $entity->getModelClass(),
            'email' => 'me@here.com'
        ]);

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->delete(route('invitations.destroy', ['invitation' => $invitation]));

        $response->assertStatus(403);
    }

    public function test_existing_members_cannot_be_invited()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $entity->id,
                'inviteable_type' => $entity->getModelClass(),
                'email' => $other_user->email,
                'role' => 'member'
            ]);

        $response->assertSessionHasErrorsIn('inviteMember', ['email']);
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_invitation_can_be_accepted()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $entity->id,
            'inviteable_type' => $entity->getModelClass(),
            'email' => $user->email
        ]);

        $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

        $response = $this->actingAs($user)->get($acceptUrl);

        $this->assertTrue($entity->fresh()->hasUserWithEmail($user->email));
        $response->assertRedirect(localized_route('entities.show', $entity));
    }

    public function test_invitation_cannot_be_accepted_unless_account_exists()
    {
        $entity = Entity::factory()->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $entity->id,
            'inviteable_type' => $entity->getModelClass(),
            'email' => 'me@here.com'
        ]);

        $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

        $response = $this->get($acceptUrl);

        $response->assertSessionHasErrors();
    }

    public function test_users_with_admin_role_can_remove_members()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\Entity')
            ->where('membership_id', $entity->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_without_admin_role_can_not_remove_members()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $membership = Membership::where('user_id', $other_user->id)
            ->where('membership_type', 'App\Models\Entity')
            ->where('membership_id', $entity->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertStatus(403);
    }

    public function test_only_administrator_can_not_remove_themself()
    {
        $user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $membership = Membership::where('user_id', $user->id)
            ->where('membership_type', 'App\Models\Entity')
            ->where('membership_id', $entity->id)
            ->first();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->delete(route('memberships.destroy', $membership));

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_with_admin_role_can_delete_entities()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('entities.edit', $entity))->delete(localized_route('entities.destroy', $entity), [
            'current_password' => 'password'
        ]);

        $response->assertRedirect('/en/dashboard');
    }

    public function test_users_with_admin_role_can_not_delete_entities_with_wrong_password()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('entities.edit', $entity))->delete(localized_route('entities.destroy', $entity), [
            'current_password' => 'wrong_password'
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_without_admin_role_can_not_delete_entities()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('entities.edit', $entity))->delete(localized_route('entities.destroy', $entity), [
            'current_password' => 'password'
        ]);

        $response->assertStatus(403);
    }

    public function test_non_members_can_not_delete_entities()
    {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_entity = Entity::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('entities.edit', $other_entity))->delete(localized_route('entities.destroy', $other_entity), [
            'current_password' => 'password'
        ]);

        $response->assertStatus(403);
    }

    public function test_users_can_view_entities()
    {
        $user = User::factory()->create();
        $entity = Entity::factory()->create();

        $response = $this->post('/en/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/en/entities');
        $response->assertStatus(200);

        $response = $this->get(localized_route('entities.show', $entity));
        $response->assertStatus(200);
    }

    public function test_guests_can_not_view_entities()
    {
        $entity = Entity::factory()->create();

        $response = $this->get('/en/entities');
        $response->assertRedirect('/en/login');

        $response = $this->get(localized_route('entities.show', $entity));
        $response->assertRedirect('/en/login');

    }
}
