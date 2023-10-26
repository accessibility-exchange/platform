<?php

use App\Enums\ProvinceOrTerritory;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Sector;
use App\Models\User;
use Database\Seeders\SectorSeeder;
use Hearth\Models\Membership;
use Illuminate\Support\Facades\URL;
use Tests\RequestFactories\UpdateRegulatedOrganizationRequestFactory;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;

test('users can create regulated organizations', function () {
    $individualUser = User::factory()->create();
    actingAs($individualUser)->get(localized_route('regulated-organizations.show-type-selection'))->assertForbidden();

    $user = User::factory()->create(['context' => 'regulated-organization']);

    actingAs($user)->get(localized_route('regulated-organizations.show-type-selection'))->assertOk();

    actingAs($user)->post(localized_route('regulated-organizations.store-type'), [
        'type' => 'government',
    ])
        ->assertRedirect(localized_route('regulated-organizations.create'))
        ->assertSessionHas('type', 'government');

    actingAs($user)->get(localized_route('regulated-organizations.create'))->assertOk();

    actingAs($user)
        ->from(localized_route('regulated-organizations.create'))
        ->post(localized_route('regulated-organizations.store'), [
            'type' => 'government',
            'name' => ['en' => 'Government Agency', 'fr' => 'Agence gouvernementale'],
        ])
        ->assertRedirect(localized_route('dashboard'));

    $regulatedOrganization = RegulatedOrganization::where('name->en', 'Government Agency')->first();

    expect($user->isMemberOf($regulatedOrganization))->toBeTrue();
    expect($user->memberships)->toHaveCount(1);

    actingAs($user)->get(localized_route('regulated-organizations.show-language-selection', $regulatedOrganization))->assertOk();

    actingAs($user)
        ->from(localized_route('regulated-organizations.show-language-selection', $regulatedOrganization))
        ->post(localized_route('regulated-organizations.store-languages', $regulatedOrganization), [
            'languages' => config('locales.supported'),
        ])
        ->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
});

test('users primary entity can be retrieved', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $user = $user->fresh();

    expect($regulatedOrganization->id)->toEqual($user->regulatedOrganization->id);
});

test('users with admin role can edit regulated organizations', function () {
    $this->seed();

    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create([
            'languages' => config('locales.supported'),
            'type' => 'business',
        ]);

    expect($regulatedOrganization->social_links)->toBeArray()->toBeEmpty();

    actingAs($user)->get(localized_route('regulated-organizations.edit', $regulatedOrganization))->assertOk();

    UpdateRegulatedOrganizationRequestFactory::new()->fake();

    actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'contact_person_vrs' => true,
    ])
        ->assertSessionHasErrors(['contact_person_phone' => 'Since you have indicated that your contact person needs VRS, please enter a phone number.']);

    actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'contact_person_phone' => '19024445678',
        'contact_person_vrs' => true,
    ])
        ->assertSessionHasNoErrors();

    $regulatedOrganization->refresh();
    expect($regulatedOrganization->contact_person_vrs)->toBeTrue();

    actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'contact_person_phone' => '19024445678',
    ])
        ->assertSessionHasNoErrors();

    $regulatedOrganization->refresh();
    expect($regulatedOrganization->contact_person_vrs)->toBeNull();

    actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => ['en' => $regulatedOrganization->name],
        'service_areas' => ['NL', 'NS'],
        'social_links' => ['facebook' => 'https://facebook.com/'.Str::slug($regulatedOrganization->name)],
        'preview' => 'Preview',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $regulatedOrganization = $regulatedOrganization->fresh();
    expect($regulatedOrganization->display_service_areas)->toBeArray()->toContain('Nova Scotia');
    expect($regulatedOrganization->accessibility_and_inclusion_links)->toHaveCount(0);
    expect($regulatedOrganization->social_links)->toHaveCount(1)->toHaveKey('facebook');

    actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => ['en' => $regulatedOrganization->name],
        'service_areas' => ['NU'],
        'accessibility_and_inclusion_links' => [['title' => 'Accessibility Statement', 'url' => 'https://example.com/accessibility']],
        'social_links' => ['facebook' => ''],
        'publish' => 'Publish',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $regulatedOrganization = $regulatedOrganization->fresh();
    expect($regulatedOrganization->display_service_areas)->toBeArray()->toContain('Nunavut');
    expect($regulatedOrganization->checkStatus('published'))->toBeTrue();
    expect($regulatedOrganization->accessibility_and_inclusion_links)->toHaveCount(1);
    expect($regulatedOrganization->social_links)->toHaveCount(0);

    actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => ['en' => $regulatedOrganization->name],
        'service_areas' => ['ON'],
        'unpublish' => 'Unpublish',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    $regulatedOrganization = $regulatedOrganization->fresh();
    expect($regulatedOrganization->checkStatus('draft'))->toBeTrue();
    expect($regulatedOrganization->display_service_areas)->toBeArray()->toContain('Ontario');

    actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => ['en' => $regulatedOrganization->name],
        'service_areas' => ['AB', 'BC'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('regulated-organizations.edit', $regulatedOrganization));
    $regulatedOrganization = $regulatedOrganization->fresh();
    expect($regulatedOrganization->display_service_areas)->toBeArray()->toContain('Alberta')->toContain('British Columbia');
});

test('users without admin role can not edit regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    actingAs($user)->get(localized_route('regulated-organizations.edit', $regulatedOrganization))->assertForbidden();

    actingAs($user)->put(localized_route('regulated-organizations.update', $regulatedOrganization), [
        'name' => $regulatedOrganization->name,
        'locality' => 'St John\'s',
        'region' => 'NL',
    ])
        ->assertForbidden();
});

test('non members can not edit regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create(['context' => 'regulated-organization']);

    $otherRegulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    actingAs($user)->get(localized_route('regulated-organizations.edit', $otherRegulatedOrganization))->assertForbidden();

    actingAs($user)->put(localized_route('regulated-organizations.update', $otherRegulatedOrganization), [
        'name' => $otherRegulatedOrganization->name,
        'locality' => 'St John\'s',
        'region' => 'NL',
    ])
        ->assertForbidden();
});

test('regulated organizations can be published', function () {
    $this->seed(SectorSeeder::class);
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create([
            'about' => 'Test about',
            'locality' => 'Toronto',
            'region' => ProvinceOrTerritory::Ontario->value,
            'service_areas' => [ProvinceOrTerritory::Ontario->value],
        ]);

    $regulatedOrganization->sectors()->attach(Sector::first()->id);

    actingAs($user)->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->put(localized_route('regulated-organizations.update-publication-status', $regulatedOrganization), [
        'publish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $regulatedOrganization = $regulatedOrganization->fresh();

    expect($regulatedOrganization->checkStatus('published'))->toBeTrue();
});

test('regulated organizations can be unpublished', function () {
    $this->seed(SectorSeeder::class);
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create([
            'about' => 'Test about',
            'locality' => 'Toronto',
            'region' => ProvinceOrTerritory::Ontario->value,
            'service_areas' => [ProvinceOrTerritory::Ontario->value],
        ]);

    $regulatedOrganization->sectors()->attach(Sector::first()->id);

    actingAs($user)->from(localized_route('regulated-organizations.edit', $regulatedOrganization))->put(localized_route('regulated-organizations.update-publication-status', $regulatedOrganization), [
        'unpublish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $regulatedOrganization = $regulatedOrganization->fresh();

    expect($regulatedOrganization->checkStatus('draft'))->toBeTrue();
});

test('regulated organization isPublishable()', function ($expected, $data, $connections = []) {
    $this->seed(SectorSeeder::class);

    // fill data so that we don't hit a Database Integrity constraint violation during creation
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $regulatedOrganization->fill($data);

    foreach ($connections as $connection) {
        if ($connection === 'sector') {
            $regulatedOrganization->sectors()->attach(Sector::first()->id);
        }
    }

    expect($regulatedOrganization->isPublishable())->toBe($expected);
})->with('regulatedOrganizationIsPublishable');

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

    $response->assertSessionHasErrors(['email']);
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

    $response = actingAs($user)->get($acceptUrl);

    expect($regulatedOrganization->fresh()->hasUserWithEmail($user->email))->toBeTrue();
    $response->assertRedirect(localized_route('dashboard'));
});

test('invitation can be declined', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => $user->email,
    ]);

    $declineUrl = route('invitations.decline', ['invitation' => $invitation]);

    $response = actingAs($user)->delete($declineUrl);

    expect($regulatedOrganization->fresh()->hasUserWithEmail($user->email))->toBeFalse();
    $this->assertModelMissing($invitation);

    $response->assertRedirect(localized_route('dashboard'));
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

    expect($regulatedOrganization->fresh()->hasUserWithEmail($user->email))->toBeFalse();
    $response->assertForbidden();
});

test('invitation can not be declined by a different user', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => fake()->email,
    ]);

    $declineUrl = route('invitations.decline', ['invitation' => $invitation]);

    actingAs($user)->delete($declineUrl)->assertForbidden();

    expect($regulatedOrganization->fresh()->hasUserWithEmail($user->email))->toBeFalse();
    $this->assertModelExists($invitation);
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

    actingAs($user)->get(localized_route('regulated-organizations.delete', $regulatedOrganization))->assertOk();

    actingAs($user)->from(localized_route('regulated-organizations.delete', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
        'current_password' => 'password',
    ])
        ->assertRedirect(localized_route('dashboard'));
});

test('users with admin role can not delete regulated organizations with wrong password', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)->from(localized_route('regulated-organizations.delete', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
        'current_password' => 'wrong_password',
    ])
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('regulated-organizations.delete', $regulatedOrganization));
});

test('users without admin role can not delete regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    actingAs($user)->get(localized_route('regulated-organizations.delete', $regulatedOrganization))->assertForbidden();

    actingAs($user)->from(localized_route('regulated-organizations.delete', $regulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $regulatedOrganization), [
        'current_password' => 'password',
    ])
        ->assertForbidden();
});

test('non members can not delete regulated organizations', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $other_user = User::factory()->create(['context' => 'regulated-organization']);

    $otherRegulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    actingAs($user)->get(localized_route('regulated-organizations.delete', $otherRegulatedOrganization))->assertForbidden();

    actingAs($user)->from(localized_route('regulated-organizations.delete', $otherRegulatedOrganization))->delete(localized_route('regulated-organizations.destroy', $otherRegulatedOrganization), [
        'current_password' => 'password',
    ])
        ->assertForbidden();
});

test('users can not view regulated organizations if they are not oriented', function () {
    $pendingUser = User::factory()->create(['oriented_at' => null]);
    actingAs($pendingUser)->get(localized_route('regulated-organizations.index'))->assertForbidden();

    $pendingUser->update(['oriented_at' => now()]);
    actingAs($pendingUser)->get(localized_route('regulated-organizations.index'))->assertOk();
});

test('organization or regulated organization users can not view regulated organizations if they are not oriented', function () {
    $organizationUser = User::factory()->create(['context' => 'organization', 'oriented_at' => null]);
    $organization = Organization::factory()->hasAttached($organizationUser, ['role' => 'admin'])->create(['oriented_at' => null]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('regulated-organizations.index'))
        ->assertForbidden();

    $organization->update(['oriented_at' => now()]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('regulated-organizations.index'))
        ->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization', 'oriented_at' => null]);
    $regulatedOrganization = RegulatedOrganization::factory()->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])->create(['oriented_at' => null]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('regulated-organizations.index'))
        ->assertForbidden();

    $regulatedOrganization->update(['oriented_at' => now()]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('regulated-organizations.index'))
        ->assertOk();
});

test('users can view regulated organizations', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['languages' => config('locales.supported'), 'published_at' => now(), 'service_areas' => ['NS']]);

    actingAs($user)->get(localized_route('regulated-organizations.index'))->assertOk();

    actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization))->assertOk();

    actingAs($user)->get(localized_route('regulated-organizations.show-projects', $regulatedOrganization))->assertOk();
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
    $this->seed(SectorSeeder::class);

    $user = User::factory()->create();
    $admin = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()->hasAttached($admin, ['role' => 'admin'])->create([
        'name' => [
            'en' => 'Canada Revenue Agency',
            'fr' => 'Agence du revenue du Canada',
            'iu' => 'ᑲᓇᑕᒥ ᐃᓐᑲᒻᑖᒃᓯᓕᕆᔨᒃᑯᑦ',
        ],
        'about' => ['en' => 'About us.'],
        'languages' => [
            'en',
            'fr',
            'asl',
            'lsq',
            'iu',
        ],
        'locality' => 'Iqaluit',
        'region' => 'NU',
        'service_areas' => ['NU'],
        'type' => 'government',
        'preferred_contact_method' => 'email',
        'published_at' => now(),
    ]);

    $regulatedOrganization->sectors()->attach(Sector::first()->id);

    $regulatedOrganization = $regulatedOrganization->fresh();

    actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization))->assertSee('Canada Revenue Agency');

    actingAs($user)->get(localized_route('regulated-organizations.show', ['regulatedOrganization' => $regulatedOrganization, 'language' => 'iu']))->assertSee('ᑲᓇᑕᒥ ᐃᓐᑲᒻᑖᒃᓯᓕᕆᔨᒃᑯᑦ');

    actingAs($user)->get(localized_route('regulated-organizations.show', ['regulatedOrganization' => $regulatedOrganization, 'language' => 'lsq']))->assertSee('Agence du revenue du Canada');
});

test('regulated organization cannot be previewed until publishable', function () {
    $this->seed(SectorSeeder::class);

    $admin = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()->hasAttached($admin, ['role' => 'admin'])->create([
        'name' => [
            'en' => 'Canada Revenue Agency',
            'fr' => 'Agence du revenue du Canada',
            'iu' => 'ᑲᓇᑕᒥ ᐃᓐᑲᒻᑖᒃᓯᓕᕆᔨᒃᑯᑦ',
        ],
        'about' => ['en' => 'About us.'],
        'languages' => [
            'en',
            'fr',
            'asl',
            'lsq',
            'iu',
        ],
        'region' => 'NU',
        'service_areas' => ['NU'],
        'type' => 'government',
        'preferred_contact_method' => 'email',
    ]);

    $regulatedOrganization->sectors()->attach(Sector::first()->id);

    actingAs($admin)->get(localized_route('regulated-organizations.show', $regulatedOrganization))->assertNotFound();

    $regulatedOrganization->update(['locality' => 'Iqaluit']);
    $regulatedOrganization = $regulatedOrganization->fresh();

    actingAs($admin)->get(localized_route('regulated-organizations.show', $regulatedOrganization))->assertOk();
});

test('regulated organizations projects functions based on project state', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create([
        'published_at' => now(),
    ]);

    $draftProject = Project::factory()->create(['published_at' => null]);
    $inProgressProject = Project::factory()->create();
    $upcomingProject = Project::factory()->create([
        'start_date' => now()->addMonth(),
        'end_date' => now()->addMonths(12),
    ]);
    $completedProject = Project::factory()->create([
        'start_date' => now()->subMonths(12),
        'end_date' => now()->subMonth(),
    ]);

    $regulatedOrganization->projects()->saveMany([
        $draftProject,
        $inProgressProject,
        $upcomingProject,
        $completedProject,
    ]);

    expect($regulatedOrganization->projects)->toHaveCount(4);
    expect($regulatedOrganization->projects->modelKeys())->toContain($draftProject->id, $inProgressProject->id, $upcomingProject->id, $completedProject->id);

    expect($regulatedOrganization->draftProjects)->toHaveCount(1);
    expect($regulatedOrganization->draftProjects->modelKeys())->toContain($draftProject->id);

    expect($regulatedOrganization->publishedProjects)->toHaveCount(3);
    expect($regulatedOrganization->publishedProjects->modelKeys())->toContain($inProgressProject->id, $upcomingProject->id, $completedProject->id);

    expect($regulatedOrganization->inProgressProjects)->toHaveCount(2);
    expect($regulatedOrganization->inProgressProjects->modelKeys())->toContain($draftProject->id, $inProgressProject->id);

    expect($regulatedOrganization->upcomingProjects)->toHaveCount(1);
    expect($regulatedOrganization->upcomingProjects->modelKeys())->toContain($upcomingProject->id);

    expect($regulatedOrganization->completedProjects)->toHaveCount(1);
    expect($regulatedOrganization->completedProjects->modelKeys())->toContain($completedProject->id);
});

test('regulated organizations have slugs in both languages even if only one is provided', function () {
    $regulatedOrg = RegulatedOrganization::factory()->create();
    expect($regulatedOrg->getTranslation('slug', 'fr', false))
        ->toEqual($regulatedOrg->getTranslation('slug', 'en', false));

    $regulatedOrg = RegulatedOrganization::factory()->create(['name' => ['fr' => 'Santé Canada']]);
    expect($regulatedOrg->getTranslation('slug', 'en', false))
        ->toEqual($regulatedOrg->getTranslation('slug', 'fr', false));
});

test('notifications can be routed for regulated organizations', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create([
        'contact_person_name' => fake()->name(),
        'contact_person_email' => fake()->email(),
        'contact_person_phone' => '19024445678',
        'preferred_contact_method' => 'email',
    ]);

    expect($regulatedOrganization->routeNotificationForVonage(new \Illuminate\Notifications\Notification()))->toEqual($regulatedOrganization->contact_person_phone);
    expect($regulatedOrganization->routeNotificationForMail(new \Illuminate\Notifications\Notification()))->toEqual([$regulatedOrganization->contact_person_email => $regulatedOrganization->contact_person_name]);
});

test('regulated organization status checks return expected state', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create([
        'published_at' => null,
        'oriented_at' => null,
        'validated_at' => null,
        'suspended_at' => null,
        'dismissed_invite_prompt_at' => null,
    ]);

    expect($regulatedOrganization->checkStatus('draft'))->toBeTrue();
    expect($regulatedOrganization->checkStatus('published'))->toBeFalse();
    expect($regulatedOrganization->checkStatus('pending'))->toBeTrue();
    expect($regulatedOrganization->checkStatus('approved'))->toBeFalse();
    expect($regulatedOrganization->checkStatus('suspended'))->toBeFalse();
    expect($regulatedOrganization->checkStatus('dismissedInvitePrompt'))->toBeFalse();

    $regulatedOrganization->published_at = now();
    $regulatedOrganization->save();

    expect($regulatedOrganization->checkStatus('draft'))->toBeFalse();
    expect($regulatedOrganization->checkStatus('published'))->toBeTrue();

    $regulatedOrganization->oriented_at = now();
    $regulatedOrganization->save();

    expect($regulatedOrganization->checkStatus('pending'))->toBeFalse();
    expect($regulatedOrganization->checkStatus('approved'))->toBeFalse();

    $regulatedOrganization->validated_at = now();
    $regulatedOrganization->save();

    expect($regulatedOrganization->checkStatus('pending'))->toBeFalse();
    expect($regulatedOrganization->checkStatus('approved'))->toBeTrue();

    $regulatedOrganization->suspended_at = now();
    $regulatedOrganization->save();

    expect($regulatedOrganization->checkStatus('suspended'))->toBeTrue();

    $regulatedOrganization->dismissed_invite_prompt_at = now();
    $regulatedOrganization->save();

    expect($regulatedOrganization->checkStatus('dismissedInvitePrompt'))->toBeTrue();
});
