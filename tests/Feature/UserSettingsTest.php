<?php

use App\Models\AccessSupport;
use App\Models\ConsultingMethod;
use App\Models\Organization;
use App\Models\PaymentType;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Database\Seeders\AccessSupportSeeder;
use Database\Seeders\ConsultingMethodSeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\PaymentTypeSeeder;
use Database\Seeders\SectorSeeder;

test('users can access settings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('settings.show'));
    $response->assertOk();
});

test('guests can not access settings', function () {
    $response = $this->get(localized_route('settings.show'));
    $response->assertRedirect(localized_route('login'));
});

test('individual users can manage access needs', function () {
    $this->seed(AccessSupportSeeder::class);

    $user = User::factory()->create(['context' => 'individual']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-access-needs'));

    $response->assertOk();

    $additionalNeeds = AccessSupport::where('name->en', 'I would like to speak to someone to discuss additional access needs or concerns')->first();

    $response = $this->actingAs($user)->put(localized_route('settings.update-access-needs'), [
        'additional_needs_or_concerns' => $additionalNeeds->id,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-access-needs'));

    $individual = $user->individual->fresh();
    expect($individual->accessSupports->pluck('id')->toArray())->toContain($additionalNeeds->id);
});

test('other users cannot manage access needs', function () {
    $this->seed(AccessSupportSeeder::class);

    $user = User::factory()->create(['context' => 'organization']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-access-needs'));

    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('settings.update-access-needs'), []);

    $response->assertForbidden();
});

test('individual users can manage communication and consultation preferences', function () {
    $this->seed(ConsultingMethodSeeder::class);

    $user = User::factory()->create(['context' => 'individual']);
    $user->individual->roles = ['participant'];
    $user->individual->save();

    $response = $this->actingAs($user)->get(localized_route('settings.edit-communication-and-consultation-preferences'));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'preferred_contact_person' => 'me',
        'email' => $user->email,
        'preferred_contact_method' => 'email',
        'consulting_methods' => [ConsultingMethod::where('name->en', 'Surveys')->first()->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'preferred_contact_person' => 'me',
        'email' => 'me@example.com',
        'preferred_contact_method' => 'email',
        'consulting_methods' => [ConsultingMethod::where('name->en', 'Surveys')->first()->id],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-communication-and-consultation-preferences'));

    expect($user->fresh()->email_verified_at)->toBeNull();

    $response = $this->actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'phone' => '902-444-4444',
        'vrs' => '1',
        'preferred_contact_person' => 'support-person',
        'support_person_phone' => '9021234567',
        'preferred_contact_method' => 'email',
        'consulting_methods' => [ConsultingMethod::where('name->en', 'Interviews')->first()->id],
    ]);

    $response->assertSessionHasErrors(['support_person_name', 'preferred_contact_method', 'meeting_types']);

    $response = $this->actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'phone' => '902-444-4444',
        'vrs' => '1',
        'preferred_contact_person' => 'support-person',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'me@here.com',
        'preferred_contact_method' => 'email',
        'consulting_methods' => [ConsultingMethod::where('name->en', 'Surveys')->first()->id],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-communication-and-consultation-preferences'));

    $user = $user->fresh();

    expect($user->phone)->toBeNull();
    expect($user->vrs)->toBeFalse();
});

test('other users cannot manage communication and consultation preferences', function () {
    $this->seed(ConsultingMethodSeeder::class);

    $user = User::factory()->create(['context' => 'organization']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-communication-and-consultation-preferences'));

    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), []);

    $response->assertForbidden();
});

test('users can manage language preferences', function () {
    $user = User::factory()->create(['context' => 'individual', 'locale' => 'en', 'signed_language' => 'ase']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-language-preferences'));

    $response->assertOk();
    $response->assertViewHas('workingLanguages', ['en', 'ase']);

    $response = $this->actingAs($user)->put(localized_route('settings.update-language-preferences'), [
        'locale' => 'en',
        'signed_language' => 'ase',
        'first_language' => 'ase',
        'working_languages' => ['ase', 'en'],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-language-preferences'));

    expect($user->signed_language)->toEqual('ase');
    expect($user->individual->first_language)->toEqual('ase');

    $newUser = User::factory()->create(['context' => 'organization']);

    $response = $this->actingAs($newUser)->get(localized_route('settings.edit-language-preferences'));

    $response->assertOk();

    $response = $this->actingAs($newUser)->put(localized_route('settings.update-language-preferences'), [
        'locale' => 'fr',
        'signed_language' => 'fcs',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-language-preferences'));

    expect($newUser->locale)->toEqual('fr');
    expect($newUser->signed_language)->toEqual('fcs');
});

test('individual user can manage payment information settings', function () {
    $this->seed(PaymentTypeSeeder::class);

    $user = User::factory()->create(['context' => 'individual']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-payment-information'));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('settings.update-payment-information'), [
        'other' => 1,
        'other_payment_type' => 'Square',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-payment-information'));

    expect($user->individual->other_payment_type)->toEqual('Square');

    $response = $this->actingAs($user)->put(localized_route('settings.update-payment-information'), [
        'payment_types' => [PaymentType::first()->id],
        'other_payment_type' => 'Square',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-payment-information'));

    expect($user->individual->fresh()->paymentTypes)->toHaveCount(1);
});

test('other users cannot access payment information settings', function () {
    $user = User::factory()->create(['context' => 'organization']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-payment-information'));

    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('settings.update-payment-information'), [
        'other' => 1,
        'other_payment_type' => 'Square',
    ]);

    $response->assertForbidden();
});

test('guest cannot access payment information settings', function () {
    $response = $this->get(localized_route('settings.edit-payment-information'));

    $response->assertRedirect(localized_route('login'));

    $response = $this->put(localized_route('settings.update-payment-information'), [
        'other' => 1,
        'other_payment_type' => 'Square',
    ]);

    $response->assertRedirect(localized_route('login'));
});

test('individual user must provide either a predefined payment type or a custom payment type', function () {
    $user = User::factory()->create(['context' => 'individual']);

    $response = $this->actingAs($user)->from(localized_route('settings.edit-payment-information'))
        ->put(localized_route('settings.update-payment-information'), [
            'other_payment_type' => '',
        ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('settings.edit-payment-information'));

    $response = $this->actingAs($user)->from(localized_route('settings.edit-payment-information'))
        ->put(localized_route('settings.update-payment-information'), [
            'other' => 1,
            'other_payment_type' => '',
        ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('settings.edit-payment-information'));
});

test('users can edit areas of interest', function () {
    $this->seed(SectorSeeder::class);
    $this->seed(ImpactSeeder::class);

    $user = User::factory()->create(['context' => 'individual']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-areas-of-interest'));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('settings.update-areas-of-interest'), []);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-areas-of-interest'));
});

test('other users cannot edit areas of interest', function () {
    $this->seed(SectorSeeder::class);
    $this->seed(ImpactSeeder::class);

    $user = User::factory()->create(['context' => 'organization']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-areas-of-interest'));

    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('settings.update-areas-of-interest'), []);

    $response->assertForbidden();
});

test('users can edit website accessibility preferences', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('settings.edit-website-accessibility-preferences'));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('settings.update-website-accessibility-preferences'), [
        'theme' => 'system',
    ]);

    $response->assertRedirect(localized_route('settings.edit-website-accessibility-preferences'));
});

test('guests can not edit website accessibility preferences', function () {
    $response = $this->get(localized_route('settings.edit-website-accessibility-preferences'));
    $response->assertRedirect(localized_route('login'));
});

test('individual and organization users can edit notification preferences', function () {
    $user = User::factory()->create(['context' => 'individual']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-notification-preferences'));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('settings.update-notification-preferences'), [
        'preferred_notification_method' => 'email',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-notification-preferences'));

    $user = User::factory()->create(['context' => 'organization']);
    Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('settings.edit-notification-preferences'));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('settings.update-notification-preferences'), [
        'preferred_notification_method' => 'email',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-notification-preferences'));
});

test('other users cannot edit notification preferences', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-notification-preferences'));
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('settings.update-notification-preferences'), []);
    $response->assertForbidden();
});

test('guests can not edit notification preferences', function () {
    $response = $this->get(localized_route('settings.edit-notification-preferences'));
    $response->assertRedirect(localized_route('login'));
});

test('users can edit roles and permissions', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->get(localized_route('settings.edit-roles-and-permissions'));
    $response->assertOk();
});

test('users can invite new members to their organization or regulated organization', function () {
    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('settings.invite-to-invitationable'));
    $response->assertOk();
    $response->assertSee('name="invitationable_id" id="invitationable_id" type="hidden" value="'.$regulatedOrganization->id.'"', false);
    $response->assertSee('name="invitationable_type" id="invitationable_type" type="hidden" value="App\Models\RegulatedOrganization"', false);

    $organizationUser = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($organizationUser)->get(localized_route('settings.invite-to-invitationable'));
    $response->assertOk();
    $response->assertSee('name="invitationable_id" id="invitationable_id" type="hidden" value="'.$organization->id.'"', false);
    $response->assertSee('name="invitationable_type" id="invitationable_type" type="hidden" value="App\Models\Organization"', false);

    $individualUser = User::factory()->create();
    $response = $this->actingAs($individualUser)->get(localized_route('settings.invite-to-invitationable'));
    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('guests can not edit roles and permissions', function () {
    $response = $this->get(localized_route('settings.edit-roles-and-permissions'));
    $response->assertRedirect(localized_route('login'));
});

test('email can be changed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('settings.edit-account-details'));
    $response->assertOk();

    $response = $this->actingAs($user)->followingRedirects()->put(localized_route('user-profile-information.update'), [
        'email' => $user->email,
    ]);
    $response->assertOk();

    $response = $this->actingAs($user)->followingRedirects()->put(localized_route('user-profile-information.update'), [
        'email' => 'me@example.net',
    ]);
    $response->assertOk();
    $response->assertSee('Please verify your email address by clicking on the link we emailed to you.');

    $user = $user->fresh();
    $this->assertEquals($user->email, 'me@example.net');
    $this->assertNull($user->email_verified_at);
});

test('password can be updated', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('settings.edit-account-details'));

    $response->assertOk();

    $response = $this->from(localized_route('settings.edit-account-details'))
        ->actingAs($user)
        ->put(localized_route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-account-details'));
});

test('password cannot be updated with incorrect current password', function () {
    $user = User::factory()->create();

    $response = $this->from(localized_route('settings.edit-account-details'))
        ->actingAs($user)
        ->put(localized_route('user-password.update'), [
            'current_password' => 'wrong_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('settings.edit-account-details'));
});

test('password cannot be updated with password that do not match', function () {
    $user = User::factory()->create();

    $response = $this->from(localized_route('settings.edit-account-details'))
        ->actingAs($user)
        ->put(localized_route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'different_new_password',
        ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('settings.edit-account-details'));
});

test('password cannot be updated with password that does not meet requirements', function () {
    $user = User::factory()->create();

    $response = $this->from(localized_route('settings.edit-account-details'))
        ->actingAs($user)
        ->put(localized_route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('settings.edit-account-details'));
});

test('users can delete their own accounts', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('settings.delete-account'));
    $response->assertOk();

    $response = $this->actingAs($user)->from(localized_route('settings.delete-account'))->delete(localized_route('users.destroy'), [
        'current_password' => 'password',
    ]);

    $this->assertGuest();

    $response->assertRedirect(localized_route('welcome'));
});

test('users cannot delete their own accounts with incorrect password', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->from(localized_route('settings.delete-account'))->delete(localized_route('users.destroy'), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertRedirect(localized_route('settings.delete-account'));
});

test('users cannot delete their own accounts without assigning other admin to organization', function () {
    $user = User::factory()->create();
    Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('settings.delete-account'))->delete(localized_route('users.destroy'), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('settings.delete-account'));
});

test('users cannot delete their own accounts without assigning other admin to regulatedOrganization', function () {
    $user = User::factory()->create();
    RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('settings.delete-account'))->delete(localized_route('users.destroy'), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('settings.delete-account'));
});

test('guests cannot delete accounts', function () {
    $user = User::factory()->create();

    $response = $this->delete(localized_route('users.destroy'));

    $response->assertRedirect(localized_route('login'));
});
