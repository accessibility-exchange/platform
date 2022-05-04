<?php

use App\Models\Invitation;
use App\Models\Membership;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('users can create regulated organizations', function () {
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
});

test('users primary entity can be retrieved', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $user = $user->fresh();

    $this->assertEquals($user->regulatedOrganization()->id, $regulatedOrganization->id);
});

test('users with admin role can edit regulated organizations', function () {
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
});

test('users without admin role can not edit regulated organizations', function () {
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
});

test('non members can not edit regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create(['context' => 'regulated-organization']);

    $otherRegulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.edit', $otherRegulatedOrganization));
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('regulated-organizations.update', $otherRegulatedOrganization), [
        'name' =>  $otherRegulatedOrganization->name,
        'locality' => 'St John\'s',
        'region' => 'NL',
    ]);
    $response->assertForbidden();
});

test('users with admin role can update other member roles', function () {
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
});

test('users without admin role can not update member roles', function () {
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
});

test('only administrator can not downgrade their role', function () {
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
});

test('users with admin role can invite members', function () {
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
});

test('users without admin role can not invite members', function () {
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
});

test('users with admin role can cancel invitations', function () {
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
});

test('users without admin role can not cancel invitations', function () {
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
});

test('existing members cannot be invited', function () {
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
});

test('invitation can be accepted', function () {
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
});

test('invitation cannot be accepted by different user', function () {
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
});

test('users with admin role can remove members', function () {
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
});

test('users without admin role can not remove members', function () {
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
});

test('only administrator can not remove themself', function () {
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
});

test('users with admin role can delete regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('dashboard'));
});

test('users with admin role can not delete entities with wrong password', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
});

test('users without admin role can not delete regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('non members can not delete regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create(['context' => 'regulated-organization']);

    $otherRegulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.edit', $otherRegulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $otherRegulatedOrganization), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('users can view regulated organizations', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.index'));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show-accessibility-and-inclusion', $regulatedOrganization));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show-projects', $regulatedOrganization));
    $response->assertOk();
});

test('guests can not view regulated organizations', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create();

    $response = $this->get(localized_route('regulated-organizations.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('regulated-organizations.show-accessibility-and-inclusion', $regulatedOrganization));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('regulated-organizations.show-projects', $regulatedOrganization));
    $response->assertRedirect(localized_route('login'));
});
