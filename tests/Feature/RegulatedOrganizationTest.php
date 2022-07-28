<?php

use App\Models\RegulatedOrganization;
use App\Models\Sector;
use App\Models\User;
use Hearth\Models\Invitation;
use Hearth\Models\Membership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

test('users can create regulated organizations', function () {
    $individualUser = User::factory()->create();
    $response = $this->actingAs($individualUser)->get(localized_route('regulated-organizations.show-type-selection'));
    $response->assertForbidden();

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show-type-selection'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('regulated-organizations.store-type'), [
        'type' => 'government',
    ]);

    $response->assertRedirect(localized_route('regulated-organizations.create'));
    $response->assertSessionHas('type', 'government');

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.create'));
    $response->assertOk();

    $response = $this->actingAs($user)
        ->from(localized_route('regulated-organizations.create'))
        ->post(localized_route('regulated-organizations.store'), [
            'type' => 'government',
            'name' => ['en' => 'Government Agency', 'fr' => 'Agence gouvernementale'],
        ]);

    $response->assertRedirect(localized_route('dashboard'));

    $regulatedOrganization = RegulatedOrganization::where('name->en', 'Government Agency')->first();

    $this->assertTrue($user->isMemberOf($regulatedOrganization));
    $this->assertEquals(1, count($user->memberships));

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show-language-selection', $regulatedOrganization));

    $response->assertOk();

    $response = $this->actingAs($user)
        ->from(localized_route('regulated-organizations.show-language-selection', $regulatedOrganization))
        ->post(localized_route('regulated-organizations.store-languages', $regulatedOrganization), [
            'languages' => ['en', 'fr', 'ase', 'fcs'],
        ]);

    $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
});

test('users primary entity can be retrieved', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $user = $user->fresh();

    $this->assertEquals($user->regulatedOrganization->id, $regulatedOrganization->id);
});

test('users with admin role can edit regulated organizations', function () {
    $this->seed();

    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create([
            'languages' => ['en', 'fr', 'ase', 'fcs'],
            'type' => 'business',
        ]);

    expect($regulatedOrganization->social_links)->toBeArray()->toBeEmpty();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.edit', $regulatedOrganization));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => ['en' => $regulatedOrganization->name],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'service_areas' => ['NL'],
        'about' => ['en' => 'TODO.'],
        'sectors' => [Sector::pluck('id')->first()],
        'social_links' => ['facebook' => 'https://facebook.com/'.Str::slug($regulatedOrganization->name)],
        'preview' => 'Preview',
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $regulatedOrganization = $regulatedOrganization->fresh();
    expect($regulatedOrganization->service_regions)->toBeArray()->toHaveKey('atlantic-provinces');
    expect($regulatedOrganization->accessibility_and_inclusion_links)->toHaveCount(0);
    expect($regulatedOrganization->social_links)->toHaveCount(1)->toHaveKey('facebook');

    $response = $this->actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => ['en' => $regulatedOrganization->name],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'service_areas' => ['NU'],
        'about' => ['en' => 'TODO.'],
        'sectors' => [Sector::pluck('id')->first()],
        'accessibility_and_inclusion_links' => [['title' => 'Accessibility Statement', 'url' => 'https://example.com/accessibility']],
        'social_links' => ['facebook' => ''],
        'publish' => 'Publish',
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));

    $regulatedOrganization = $regulatedOrganization->fresh();
    expect($regulatedOrganization->service_regions)->toBeArray()->toHaveKey('northern-territories');
    expect($regulatedOrganization->checkStatus('published'))->toBeTrue();
    expect($regulatedOrganization->accessibility_and_inclusion_links)->toHaveCount(1);
    expect($regulatedOrganization->social_links)->toHaveCount(0);

    $response = $this->actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => ['en' => $regulatedOrganization->name],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'service_areas' => ['ON'],
        'about' => ['en' => 'TODO.'],
        'sectors' => [Sector::pluck('id')->first()],
        'accessibility_and_inclusion_links' => [['title' => 'Accessibility Statement', 'url' => 'https://example.com/accessibility']],
        'social_links' => ['facebook' => ''],
        'unpublish' => 'Unpublish',
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    $regulatedOrganization = $regulatedOrganization->fresh();
    expect($regulatedOrganization->checkStatus('draft'))->toBeTrue();
    expect($regulatedOrganization->service_regions)->toBeArray()->toHaveKey('central-canada');

    $response = $this->actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => ['en' => $regulatedOrganization->name],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'service_areas' => ['AB', 'BC'],
        'about' => ['en' => 'TODO.'],
        'sectors' => [Sector::pluck('id')->first()],
        'accessibility_and_inclusion_links' => [['title' => 'Accessibility Statement', 'url' => 'https://example.com/accessibility']],
        'social_links' => ['facebook' => ''],
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    $regulatedOrganization = $regulatedOrganization->fresh();
    expect($regulatedOrganization->service_regions)->toBeArray()->toHaveKey('prairie-provinces')->toHaveKey('west-coast');
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
        'name' => $otherRegulatedOrganization->name,
        'locality' => 'St John\'s',
        'region' => 'NL',
    ]);
    $response->assertForbidden();
});

test('regulated organizations can be published', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->put(localized_route('regulated-organizations.update-publication-status', $regulatedOrganization), [
        'publish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $regulatedOrganization = $regulatedOrganization->fresh();

    $this->assertTrue($regulatedOrganization->checkStatus('published'));
});

test('regulated organizations can be unpublished', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->put(localized_route('regulated-organizations.update-publication-status', $regulatedOrganization), [
        'unpublish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $regulatedOrganization = $regulatedOrganization->fresh();

    $this->assertTrue($regulatedOrganization->checkStatus('draft'));
});

test('users with admin role can update other member roles', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\RegulatedOrganization')
        ->where('membershipable_id', $regulatedOrganization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'admin',
        ]);
    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role can not update member roles', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\RegulatedOrganization')
        ->where('membershipable_id', $regulatedOrganization->id)
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
    $yet_another_user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'admin'])
        ->hasAttached($yet_another_user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\RegulatedOrganization')
        ->where('membershipable_id', $regulatedOrganization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'member',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\RegulatedOrganization')
        ->where('membershipable_id', $regulatedOrganization->id)
        ->first();

    $response = $this
        ->actingAs($other_user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'member',
        ]);

    $response->assertSessionHasErrors(['role']);
    $response->assertRedirect(localized_route('memberships.edit', $membership));
});

test('users with admin role can invite members', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('settings.invite-to-invitationable'))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $regulatedOrganization->id,
            'invitationable_type' => get_class($regulatedOrganization),
            'email' => 'newuser@here.com',
            'role' => 'member',
        ]);

    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role can not invite members', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('settings.invite-to-invitationable'))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $regulatedOrganization->id,
            'invitationable_type' => get_class($regulatedOrganization),
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
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => 'me@here.com',
    ]);

    $response = $this
        ->actingAs($user)
        ->from(localized_route('settings.invite-to-invitationable'))
        ->delete(route('invitations.destroy', ['invitation' => $invitation]));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role can not cancel invitations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => 'me@here.com',
    ]);

    $response = $this
        ->actingAs($user)
        ->from(localized_route('settings.invite-to-invitationable'))
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
        ->from(localized_route('settings.invite-to-invitationable'))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $regulatedOrganization->id,
            'invitationable_type' => get_class($regulatedOrganization),
            'email' => $other_user->email,
            'role' => 'member',
        ]);

    $response->assertSessionHasErrorsIn('inviteMember', ['email']);
    $response->assertRedirect(localized_route('settings.invite-to-invitationable'));
});

test('invitation can be accepted', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
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
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
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
        ->where('membershipable_type', 'App\Models\RegulatedOrganization')
        ->where('membershipable_id', $regulatedOrganization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('settings.edit-roles-and-permissions'))
        ->delete(route('memberships.destroy', $membership));

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role can not remove members', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\RegulatedOrganization')
        ->where('membershipable_id', $regulatedOrganization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('settings.edit-roles-and-permissions'))
        ->delete(route('memberships.destroy', $membership));

    $response->assertForbidden();
});

test('sole administrator can not remove themself', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\RegulatedOrganization')
        ->where('membershipable_id', $regulatedOrganization->id)
        ->first();

    $response = $this
        ->actingAs($user)
        ->from(localized_route('settings.edit-roles-and-permissions'))
        ->delete(route('memberships.destroy', $membership));

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users with admin role can delete regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.delete', $regulatedOrganization));
    $response->assertOk();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.delete', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('dashboard'));
});

test('users with admin role can not delete entities with wrong password', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.delete', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('regulated-organizations.delete', $regulatedOrganization));
});

test('users without admin role can not delete regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.delete', $regulatedOrganization));
    $response->assertForbidden();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.delete', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
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

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.delete', $otherRegulatedOrganization));
    $response->assertForbidden();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.delete', $otherRegulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $otherRegulatedOrganization), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('users can view regulated organizations', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['languages' => ['en', 'fr', 'ase', 'fcs']]);

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.index'));
    $response->assertOk();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
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

    $response = $this->get(localized_route('regulated-organizations.show-projects', $regulatedOrganization));
    $response->assertRedirect(localized_route('login'));
});

test('user can view regulated organization in different languages', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()->hasAttached($admin, ['role' => 'admin'])->create([
        'name' => [
            'en' => 'Canada Revenue Agency',
            'fr' => 'Agence du revenue du Canada',
            'iu' => 'ᑲᓇᑕᒥ ᐃᓐᑲᒻᑖᒃᓯᓕᕆᔨᒃᑯᑦ',
        ],
        'languages' => [
            'en',
            'fr',
            'ase',
            'fcs',
            'iu',
        ],
    ]);

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertSee('Canada Revenue Agency');

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', ['regulatedOrganization' => $regulatedOrganization, 'language' => 'iu']));
    $response->assertSee('ᑲᓇᑕᒥ ᐃᓐᑲᒻᑖᒃᓯᓕᕆᔨᒃᑯᑦ');

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', ['regulatedOrganization' => $regulatedOrganization, 'language' => 'fcs']));
    $response->assertSee('Agence du revenue du Canada');
});
