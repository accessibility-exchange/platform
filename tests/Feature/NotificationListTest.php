<?php

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;

test('only individual users can have a notification list', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('notification-list.show'));

    $response->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('notification-list.show'));

    $response->assertForbidden();
});

test('individual users can add and remove regulated organizations from their notification list', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => ['en' => 'Umbrella Corporation']]);
    $regulatedOrganization->publish();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertSee('Add to my notification list');

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('notification-list.add'), [
            'notificationable_type' => get_class($regulatedOrganization),
            'notificationable_id' => $regulatedOrganization->id,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertSee('Remove from my notification list');

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('notification-list.remove'), [
            'notificationable_type' => get_class($regulatedOrganization),
            'notificationable_id' => $regulatedOrganization->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $user = $user->fresh();

    expect($user->regulatedOrganizationsForNotification)->toHaveCount(0);

    $this->actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('notification-list.add'), [
            'notificationable_type' => get_class($regulatedOrganization),
            'notificationable_id' => $regulatedOrganization->id,
        ]);

    expect($regulatedOrganization->isNotifying($user))->toBeTrue();

    $response = $this->actingAs($user)->get(localized_route('notification-list.show'));
    $response->assertSee('Umbrella Corporation');

    $response = $this->actingAs($user)->from(localized_route('notification-list.show'))
        ->post(localized_route('notification-list.remove'), [
            'notificationable_type' => get_class($regulatedOrganization),
            'notificationable_id' => $regulatedOrganization->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('notification-list.show'));

    $user = $user->fresh();

    expect($user->regulatedOrganizationsForNotification)->toHaveCount(0);

    $nullUser = null;
    expect($regulatedOrganization->isNotifying($nullUser))->toBeFalse();
});

test('individual users can add and remove organizations from their notification list', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['name' => ['en' => 'Umbrella Corporation'], 'published_at' => now()]);

    $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
    $response->assertSee('Block');

    $response = $this->actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('notification-list.add'), [
            'notificationable_type' => get_class($organization),
            'notificationable_id' => $organization->id,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.show', $organization));

    expect($organization->isNotifying($user))->toBeTrue();

    $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
    $response->assertSee('Remove from my notification list');

    $response = $this->actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('notification-list.remove'), [
            'notificationable_type' => get_class($organization),
            'notificationable_id' => $organization->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('organizations.show', $organization));

    $user = $user->fresh();

    expect($user->organizationsForNotification)->toHaveCount(0);

    $this->actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('notification-list.add'), [
            'notificationable_type' => get_class($organization),
            'notificationable_id' => $organization->id,
        ]);

    $response = $this->actingAs($user)->get(localized_route('notification-list.show'));
    $response->assertSee('Umbrella Corporation');

    $response = $this->actingAs($user)->from(localized_route('notification-list.show'))
        ->post(localized_route('notification-list.remove'), [
            'notificationable_type' => get_class($organization),
            'notificationable_id' => $organization->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('notification-list.show'));

    $user = $user->fresh();

    expect($user->organizationsForNotification)->toHaveCount(0);

    $nullUser = null;
    expect($organization->isNotifying($nullUser))->toBeFalse();
});
