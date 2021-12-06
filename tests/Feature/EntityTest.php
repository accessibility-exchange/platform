<?php

namespace Tests\Feature;

use App\Models\Entity;
use App\Models\Invitation;
use App\Models\Membership;
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
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);

        $response = $this->actingAs($user)->get(localized_route('entities.create'));
        $response->assertOk();

        $response = $this->actingAs($user)->post(localized_route('entities.create'), [
            'name' => $user->name . ' Inc.',
            'locality' => 'Halifax',
            'region' => 'NS',
        ]);

        $url = localized_route('entities.show', ['entity' => Str::slug($user->name . ' Inc.')]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect($url);

        $entity = Entity::where('name', $user->name . ' Inc.')->first();

        $this->assertTrue($user->isMemberOf($entity));
        $this->assertEquals(count($user->memberships), 1);
    }

    public function test_users_primary_entity_can_be_retrieved()
    {
        $user = User::factory()->create(['context' => 'entity']);
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $user = $user->fresh();

        $this->assertEquals($user->entity()->id, $entity->id);
    }

    public function test_users_with_admin_role_can_edit_entities()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('entities.edit', $entity));
        $response->assertOk();

        $response = $this->actingAs($user)->put(localized_route('entities.update', $entity), [
            'name' => $entity->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertRedirect(localized_route('entities.show', $entity));
    }

    public function test_users_without_admin_role_can_not_edit_entities()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('entities.edit', $entity));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('entities.update', $entity), [
            'name' => $entity->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertForbidden();
    }

    public function test_non_members_can_not_edit_entities()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $other_user = User::factory()->create(['context' => 'entity']);

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_entity = Entity::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->actingAs($user)->get(localized_route('entities.edit', $other_entity));
        $response->assertForbidden();

        $response = $this->actingAs($user)->put(localized_route('entities.update', $other_entity), [
            'name' => $other_entity->name,
            'locality' => 'St John\'s',
            'region' => 'NL',
        ]);
        $response->assertForbidden();
    }

    public function test_users_with_admin_role_can_update_other_member_roles()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $other_user = User::factory()->create(['context' => 'entity']);

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
                'role' => 'admin',
            ]);
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_without_admin_role_can_not_update_member_roles()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);

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
                'role' => 'admin',
            ]);

        $response->assertForbidden();
    }

    public function test_only_administrator_can_not_downgrade_their_role()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $other_user = User::factory()->create(['context' => 'entity']);

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
                'role' => 'member',
            ]);

        $response->assertSessionHasErrors(['membership']);
        $response->assertRedirect(localized_route('memberships.edit', $membership));
    }

    public function test_users_with_admin_role_can_invite_members()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $entity->id,
                'inviteable_type' => get_class($entity),
                'email' => 'newuser@here.com',
                'role' => 'member',
            ]);

        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_without_admin_role_can_not_invite_members()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $entity->id,
                'inviteable_type' => get_class($entity),
                'email' => 'newuser@here.com',
                'role' => 'member',
            ]);

        $response->assertForbidden();
    }

    public function test_users_with_admin_role_can_cancel_invitations()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $entity->id,
            'inviteable_type' => get_class($entity),
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
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $entity->id,
            'inviteable_type' => get_class($entity),
            'email' => 'me@here.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->delete(route('invitations.destroy', ['invitation' => $invitation]));

        $response->assertForbidden();
    }

    public function test_existing_members_cannot_be_invited()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $other_user = User::factory()->create(['context' => 'entity']);

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->hasAttached($other_user, ['role' => 'member'])
            ->create();

        $response = $this
            ->actingAs($user)
            ->from(localized_route('entities.edit', ['entity' => $entity]))
            ->post(localized_route('invitations.create'), [
                'inviteable_id' => $entity->id,
                'inviteable_type' => get_class($entity),
                'email' => $other_user->email,
                'role' => 'member',
            ]);

        $response->assertSessionHasErrorsIn('inviteMember', ['email']);
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_invitation_can_be_accepted()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $entity = Entity::factory()->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $entity->id,
            'inviteable_type' => get_class($entity),
            'email' => $user->email,
        ]);

        $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

        $response = $this->actingAs($user)->get($acceptUrl);

        $this->assertTrue($entity->fresh()->hasUserWithEmail($user->email));
        $response->assertRedirect(localized_route('entities.show', $entity));
    }

    public function test_invitation_cannot_be_accepted_by_different_user()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $other_user = User::factory()->create(['context' => 'entity']);
        $entity = Entity::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();
        $invitation = Invitation::factory()->create([
            'inviteable_id' => $entity->id,
            'inviteable_type' => get_class($entity),
            'email' => $user->email,
        ]);

        $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

        $response = $this->from(localized_route('dashboard'))->actingAs($other_user)->get($acceptUrl);

        $this->assertFalse($entity->fresh()->hasUserWithEmail($user->email));
        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_with_admin_role_can_remove_members()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $other_user = User::factory()->create(['context' => 'entity']);

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
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $other_user = User::factory()->create(['context' => 'entity']);

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

        $response->assertForbidden();
    }

    public function test_only_administrator_can_not_remove_themself()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);

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
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('entities.edit', $entity))->delete(localized_route('entities.destroy', $entity), [
            'current_password' => 'password',
        ]);

        $response->assertRedirect(localized_route('dashboard'));
    }

    public function test_users_with_admin_role_can_not_delete_entities_with_wrong_password()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('entities.edit', $entity))->delete(localized_route('entities.destroy', $entity), [
            'current_password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect(localized_route('entities.edit', $entity));
    }

    public function test_users_without_admin_role_can_not_delete_entities()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'member'])
            ->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('entities.edit', $entity))->delete(localized_route('entities.destroy', $entity), [
            'current_password' => 'password',
        ]);

        $response->assertForbidden();
    }

    public function test_non_members_can_not_delete_entities()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create(['context' => 'entity']);
        $other_user = User::factory()->create(['context' => 'entity']);

        $entity = Entity::factory()
            ->hasAttached($user, ['role' => 'admin'])
            ->create();

        $other_entity = Entity::factory()
            ->hasAttached($other_user, ['role' => 'admin'])
            ->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->from(localized_route('entities.edit', $other_entity))->delete(localized_route('entities.destroy', $other_entity), [
            'current_password' => 'password',
        ]);

        $response->assertForbidden();
    }

    public function test_users_can_view_entities()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $user = User::factory()->create();
        $entity = Entity::factory()->create();

        $response = $this->post(localized_route('login-store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(localized_route('entities.index'));
        $response->assertOk();

        $response = $this->get(localized_route('entities.show', $entity));
        $response->assertOk();

        $response = $this->get(localized_route('entities.show-accessibility-and-inclusion', $entity));
        $response->assertOk();

        $response = $this->get(localized_route('entities.show-projects', $entity));
        $response->assertOk();
    }

    public function test_guests_can_not_view_entities()
    {
        if (! config('hearth.entities.enabled')) {
            return $this->markTestSkipped('Entity support is not enabled.');
        }

        $entity = Entity::factory()->create();

        $response = $this->get(localized_route('entities.index'));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('entities.show', $entity));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('entities.show-accessibility-and-inclusion', $entity));
        $response->assertRedirect(localized_route('login'));

        $response = $this->get(localized_route('entities.show-projects', $entity));
        $response->assertRedirect(localized_route('login'));
    }
}
