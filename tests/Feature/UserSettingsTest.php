<?php

use App\Models\AccessSupport;
use App\Models\Engagement;
use App\Models\Organization;
use App\Models\PaymentType;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Database\Seeders\AccessSupportSeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\PaymentTypeSeeder;
use Database\Seeders\SectorSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\delete;
use function Pest\Laravel\from;
use function Pest\Laravel\get;
use function Pest\Laravel\put;
use function Pest\Laravel\seed;

test('users can access settings', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('settings.show'))
        ->assertOk();
});

test('guests can not access settings', function () {
    get(localized_route('settings.show'))
        ->assertRedirect(localized_route('login'));
});

test('individual users can manage access needs', function () {
    seed(AccessSupportSeeder::class);

    $user = User::factory()->create(['context' => 'individual']);

    $individual = $user->individual;
    $individual->update(['region' => 'NL']);
    $individual = $individual->fresh();

    actingAs($user)->get(localized_route('settings.edit-access-needs'))
        ->assertOk();

    $additionalNeeds = AccessSupport::where('name->en', 'I would like to speak to someone to discuss additional access needs or concerns')->first();

    actingAs($user)->put(localized_route('settings.update-access-needs'), [
        'additional_needs_or_concerns' => $additionalNeeds->id,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-access-needs'));

    $individual = $individual->fresh();
    expect($individual->accessSupports->pluck('id')->toArray())->toContain($additionalNeeds->id);
    expect($individual->region)->toEqual('NL');
});

test('other users cannot manage access needs', function () {
    seed(AccessSupportSeeder::class);

    $user = User::factory()->create(['context' => 'organization']);

    actingAs($user)->get(localized_route('settings.edit-access-needs'))
        ->assertForbidden();

    actingAs($user)->put(localized_route('settings.update-access-needs'), [])
        ->assertForbidden();
});

test('access needs save redirect', function () {
    seed(AccessSupportSeeder::class);

    $engagement = Engagement::factory()->create();
    $user = User::factory()->create(['context' => 'individual']);

    // access needs page without engagement parameter
    actingAs($user)->get(localized_route('settings.edit-access-needs'))
        ->assertOk()
        ->assertSee('<button>'.__('Save').'</button>', false);

    // access needs page with invalid engagement parameter
    actingAs($user)->get(localized_route('settings.edit-access-needs', ['engagement' => 1000]))
        ->assertOk()
        ->assertSee('<button>'.__('Save').'</button>', false);

    // access needs page with valid engagement parameter
    actingAs($user)->get(localized_route('settings.edit-access-needs', ['engagement' => $engagement->id]))
        ->assertOk()
        ->assertSeeText(__('Save and back to confirm access needs'));
});

test('individual users can manage communication and consultation preferences', function () {
    $user = User::factory()->create(['context' => 'individual']);
    $user->individual->roles = ['participant'];
    $user->individual->save();

    actingAs($user)->get(localized_route('settings.edit-communication-and-consultation-preferences'))
        ->assertOk();

    actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'preferred_contact_person' => 'me',
        'email' => $user->email,
        'preferred_contact_method' => 'email',
        'consulting_methods' => ['survey'],
    ]);

    actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'preferred_contact_person' => 'me',
        'email' => 'me@example.com',
        'preferred_contact_method' => 'email',
        'consulting_methods' => ['survey'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-communication-and-consultation-preferences'));

    expect($user->fresh()->email_verified_at)->toBeNull();

    actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'phone' => '902-444-4444',
        'vrs' => '1',
        'preferred_contact_person' => 'support-person',
        'support_person_phone' => '9021234567',
        'preferred_contact_method' => 'email',
        'consulting_methods' => ['interviews'],
    ])->assertSessionHasErrors(['support_person_name', 'support_person_email', 'meeting_types']);

    actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'phone' => '902-444-4444',
        'vrs' => '1',
        'preferred_contact_person' => 'support-person',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'me@here.com',
        'preferred_contact_method' => 'email',
        'consulting_methods' => [],
    ])->assertSessionHasErrors(['consulting_methods']);

    actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'phone' => '902-444-4444',
        'vrs' => '1',
        'preferred_contact_person' => 'support-person',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'me@here.com',
        'preferred_contact_method' => 'email',
        'consulting_methods' => ['survey'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-communication-and-consultation-preferences'));

    $user = $user->fresh();

    expect($user->phone)->toBeNull();
    expect($user->vrs)->toBeFalse();
});

test('other users cannot manage communication and consultation preferences', function () {
    $user = User::factory()->create(['context' => 'organization']);

    actingAs($user)->get(localized_route('settings.edit-communication-and-consultation-preferences'))
        ->assertForbidden();

    actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [])
        ->assertForbidden();
});

test('users can manage language preferences', function () {
    $user = User::factory()->create(['context' => 'individual', 'locale' => 'asl']);

    actingAs($user)->get(localized_route('settings.edit-language-preferences'))
        ->assertOk()
        ->assertViewHas('workingLanguages', ['asl']);

    actingAs($user)->put(localized_route('settings.update-language-preferences'), [
        'locale' => 'asl',
        'first_language' => 'asl',
        'working_languages' => ['asl', 'en'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-language-preferences'));

    expect($user->locale)->toEqual('asl');
    expect($user->individual->first_language)->toEqual('asl');

    $newUser = User::factory()->create(['context' => 'organization']);

    actingAs($newUser)->get(localized_route('settings.edit-language-preferences'))
        ->assertOk();

    actingAs($newUser)->put(localized_route('settings.update-language-preferences'), [
        'locale' => 'lsq',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-language-preferences'));

    expect($newUser->locale)->toEqual('lsq');
});

test('individual user can manage payment information settings', function () {
    seed(PaymentTypeSeeder::class);

    $user = User::factory()->create(['context' => 'individual']);

    actingAs($user)->get(localized_route('settings.edit-payment-information'))
        ->assertOk();

    actingAs($user)->put(localized_route('settings.update-payment-information'), [
        'other' => 1,
        'other_payment_type' => 'Square',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-payment-information'));

    expect($user->individual->other_payment_type)->toEqual('Square');

    actingAs($user)->put(localized_route('settings.update-payment-information'), [
        'payment_types' => [PaymentType::first()->id],
        'other_payment_type' => 'Square',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-payment-information'));

    expect($user->individual->fresh()->paymentTypes)->toHaveCount(1);
});

test('other users cannot access payment information settings', function () {
    $user = User::factory()->create(['context' => 'organization']);

    actingAs($user)->get(localized_route('settings.edit-payment-information'))
        ->assertForbidden();

    actingAs($user)->put(localized_route('settings.update-payment-information'), [
        'other' => 1,
        'other_payment_type' => 'Square',
    ])->assertForbidden();
});

test('guest cannot access payment information settings', function () {
    get(localized_route('settings.edit-payment-information'))
        ->assertRedirect(localized_route('login'));

    put(localized_route('settings.update-payment-information'), [
        'other' => 1,
        'other_payment_type' => 'Square',
    ])->assertRedirect(localized_route('login'));
});

test('individual user must provide either a predefined payment type or a custom payment type', function () {
    $user = User::factory()->create(['context' => 'individual']);

    actingAs($user)->from(localized_route('settings.edit-payment-information'))
        ->put(localized_route('settings.update-payment-information'), [
            'other_payment_type' => '',
        ])
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('settings.edit-payment-information'));

    actingAs($user)->from(localized_route('settings.edit-payment-information'))
        ->put(localized_route('settings.update-payment-information'), [
            'other' => 1,
            'other_payment_type' => '',
        ])
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('settings.edit-payment-information'));
});

test('users can edit areas of interest', function () {
    seed(SectorSeeder::class);
    seed(ImpactSeeder::class);

    $user = User::factory()->create(['context' => 'individual']);

    actingAs($user)->get(localized_route('settings.edit-areas-of-interest'))
        ->assertOk();

    actingAs($user)->put(localized_route('settings.update-areas-of-interest'), [])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-areas-of-interest'));
});

test('other users cannot edit areas of interest', function () {
    seed(SectorSeeder::class);
    seed(ImpactSeeder::class);

    $user = User::factory()->create(['context' => 'organization']);

    actingAs($user)->get(localized_route('settings.edit-areas-of-interest'))
        ->assertForbidden();

    actingAs($user)->put(localized_route('settings.update-areas-of-interest'), [])
        ->assertForbidden();
});

test('users can edit website accessibility preferences', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('settings.edit-website-accessibility-preferences'))
        ->assertOk();

    actingAs($user)->put(localized_route('settings.update-website-accessibility-preferences'), [
        'theme' => 'dark',
        'text_to_speech' => false,
    ])
        ->assertRedirect(localized_route('settings.edit-website-accessibility-preferences'))
        ->assertPlainCookie('theme', 'dark');
});

test('guests can not edit website accessibility preferences', function () {
    get(localized_route('settings.edit-website-accessibility-preferences'))
        ->assertRedirect(localized_route('login'));
});

test('individual and organization users can edit notification preferences', function () {
    $user = User::factory()->create(['context' => 'individual']);

    actingAs($user)->get(localized_route('settings.edit-notification-preferences'))
        ->assertOk();

    actingAs($user)->put(localized_route('settings.update-notification-preferences'), [
        'preferred_notification_method' => 'email',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-notification-preferences'));

    $user = User::factory()->create(['context' => 'organization']);
    Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)->get(localized_route('settings.edit-notification-preferences'))
        ->assertOk();

    actingAs($user)->put(localized_route('settings.update-notification-preferences'), [
        'preferred_notification_method' => 'email',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-notification-preferences'));
});

test('other users cannot edit notification preferences', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    actingAs($user)->get(localized_route('settings.edit-notification-preferences'))
        ->assertForbidden();

    actingAs($user)->put(localized_route('settings.update-notification-preferences'), [])
        ->assertForbidden();
});

test('guests can not edit notification preferences', function () {
    get(localized_route('settings.edit-notification-preferences'))
        ->assertRedirect(localized_route('login'));
});

test('users belonging to an organization or regulated organization can edit roles and permissions', function () {
    $organizationUserWithoutOrganization = User::factory()->create(['context' => 'organization']);

    actingAs($organizationUserWithoutOrganization)->get(localized_route('settings.edit-roles-and-permissions'))
        ->assertForbidden();

    $organizationUserWithOrganization = User::factory()->create(['context' => 'organization']);

    Organization::factory()
        ->hasAttached($organizationUserWithOrganization, ['role' => 'admin'])
        ->create();

    actingAs($organizationUserWithOrganization)->get(localized_route('settings.edit-roles-and-permissions'))
        ->assertOk();

    $organizationUserWithOrganization->update(['suspended_at' => now()]);
    $organizationUserWithOrganization = $organizationUserWithOrganization->fresh();

    actingAs($organizationUserWithOrganization)->get(localized_route('settings.edit-roles-and-permissions'))
        ->assertForbidden();

    $regulatedOrganizationUserWithoutOrganization = User::factory()->create(['context' => 'regulated-organization']);

    actingAs($regulatedOrganizationUserWithoutOrganization)->get(localized_route('settings.edit-roles-and-permissions'))
        ->assertForbidden();

    $regulatedOrganizationUserWithOrganization = User::factory()->create(['context' => 'regulated-organization']);

    RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUserWithOrganization, ['role' => 'admin'])
        ->create();

    actingAs($regulatedOrganizationUserWithOrganization)->get(localized_route('settings.edit-roles-and-permissions'))
        ->assertOk();

    $regulatedOrganizationUserWithOrganization->update(['suspended_at' => now()]);
    $regulatedOrganizationUserWithOrganization = $regulatedOrganizationUserWithOrganization->fresh();

    actingAs($regulatedOrganizationUserWithOrganization)->get(localized_route('settings.edit-roles-and-permissions'))
        ->assertForbidden();
});

test('users belonging to an organization or regulated organization can invite new members to their organization or regulated organization', function () {
    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])
        ->create();

    actingAs($regulatedOrganizationUser)->get(localized_route('settings.invite-to-invitationable'))
        ->assertSee('name="invitationable_id" id="invitationable_id" type="hidden" value="'.$regulatedOrganization->id.'"', false)
        ->assertOk()
        ->assertSee('name="invitationable_type" id="invitationable_type" type="hidden" value="App\Models\RegulatedOrganization"', false);

    $organizationUser = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => 'admin'])
        ->create();

    actingAs($organizationUser)->get(localized_route('settings.invite-to-invitationable'))
        ->assertOk()
        ->assertSee('name="invitationable_id" id="invitationable_id" type="hidden" value="'.$organization->id.'"', false)
        ->assertSee('name="invitationable_type" id="invitationable_type" type="hidden" value="App\Models\Organization"', false);

    $individualUser = User::factory()->create();

    actingAs($individualUser)->get(localized_route('settings.invite-to-invitationable'))
        ->assertForbidden();
});

test('guests can not edit roles and permissions', function () {
    get(localized_route('settings.edit-roles-and-permissions'))
        ->assertRedirect(localized_route('login'));
});

test('email can be changed', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('settings.edit-account-details'))
        ->assertOk();

    actingAs($user)->followingRedirects()->put(localized_route('user-profile-information.update'), [
        'email' => $user->email,
    ])->assertOk();

    actingAs($user)->followingRedirects()->put(localized_route('user-profile-information.update'), [
        'email' => 'me@example.net',
    ])
        ->assertOk()
        ->assertSee('Please verify your email address by clicking on the link we emailed to you.');

    $user = $user->fresh();
    expect('me@example.net')->toEqual($user->email);
    expect($user->email_verified_at)->toBeNull();
});

test('password can be updated', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('settings.edit-account-details'))
        ->assertOk();

    from(localized_route('settings.edit-account-details'))
        ->actingAs($user)
        ->put(localized_route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'correctHorse-batteryStaple7',
            'password_confirmation' => 'correctHorse-batteryStaple7',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-account-details'));
});

test('password cannot be updated with incorrect current password', function () {
    $user = User::factory()->create();

    from(localized_route('settings.edit-account-details'))
        ->actingAs($user)
        ->put(localized_route('user-password.update'), [
            'current_password' => 'wrong_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ])
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('settings.edit-account-details'));
});

test('password cannot be updated with password that do not match', function () {
    $user = User::factory()->create();

    from(localized_route('settings.edit-account-details'))
        ->actingAs($user)
        ->put(localized_route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'different_new_password',
        ])
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('settings.edit-account-details'));
});

test('password cannot be updated with password that does not meet requirements', function () {
    $user = User::factory()->create();

    from(localized_route('settings.edit-account-details'))
        ->actingAs($user)
        ->put(localized_route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ])
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('settings.edit-account-details'));
});

test('users can delete their own accounts', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('settings.delete-account'))
        ->assertOk();

    actingAs($user)->from(localized_route('settings.delete-account'))->delete(localized_route('users.destroy'), [
        'current_password' => 'password',
    ])->assertRedirect(localized_route('welcome'));

    assertGuest();
});

test('users cannot delete their own accounts with incorrect password', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->from(localized_route('settings.delete-account'))->delete(localized_route('users.destroy'), [
        'current_password' => 'wrong_password',
    ])->assertRedirect(localized_route('settings.delete-account'));
});

test('users cannot delete their own accounts without assigning other admin to organization', function () {
    $user = User::factory()->create();
    Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)->from(localized_route('settings.delete-account'))->delete(localized_route('users.destroy'), [
        'current_password' => 'password',
    ])->assertRedirect(localized_route('settings.delete-account'));
});

test('users cannot delete their own accounts without assigning other admin to regulatedOrganization', function () {
    $user = User::factory()->create();
    RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)->from(localized_route('settings.delete-account'))->delete(localized_route('users.destroy'), [
        'current_password' => 'password',
    ])->assertRedirect(localized_route('settings.delete-account'));
});

test('guests cannot delete accounts', function () {
    $user = User::factory()->create();

    delete(localized_route('users.destroy'))
        ->assertRedirect(localized_route('login'));
});
