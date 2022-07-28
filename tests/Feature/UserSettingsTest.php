<?php

use App\Models\ConsultingMethod;
use App\Models\IndividualRole;
use App\Models\Organization;
use App\Models\PaymentType;
use App\Models\User;
use Database\Seeders\AccessSupportSeeder;
use Database\Seeders\ConsultingMethodSeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\IndividualRoleSeeder;
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

    $response = $this->actingAs($user)->put(localized_route('settings.update-access-needs'), []);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-access-needs'));
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
    $this->seed(IndividualRoleSeeder::class);

    $user = User::factory()->create(['context' => 'individual']);
    $user->individual->individualRoles()->attach(IndividualRole::where('name->en', 'Consultation Participant')->first()->id);

    $response = $this->actingAs($user)->get(localized_route('settings.edit-communication-and-consultation-preferences'));

    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'preferred_contact_person' => 'me',
        'email' => $user->email,
        'preferred_contact_method' => 'email',
        'consulting_methods' => [ConsultingMethod::where('name->en', 'Surveys')->first()->id],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('settings.edit-communication-and-consultation-preferences'));

    $response = $this->actingAs($user)->put(localized_route('settings.update-communication-and-consultation-preferences'), [
        'preferred_contact_person' => 'support-person',
        'support_person_phone' => '9021234567',
        'preferred_contact_method' => 'email',
        'consulting_methods' => [ConsultingMethod::where('name->en', 'Interviews')->first()->id],
    ]);

    $response->assertSessionHasErrors(['support_person_name', 'preferred_contact_method', 'meeting_types']);
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
