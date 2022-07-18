<?php

use App\Models\PaymentType;
use App\Models\User;
use Database\Seeders\PaymentTypeSeeder;

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
