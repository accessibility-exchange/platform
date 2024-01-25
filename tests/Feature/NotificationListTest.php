<?php

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('only individual users can have a notification list', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('notification-list.show'))
        ->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);

    actingAs($regulatedOrganizationUser)->get(localized_route('notification-list.show'))
        ->assertForbidden();
});

test('individual users can add and remove regulated organizations from their notification list', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => ['en' => 'Umbrella Corporation']]);
    $regulatedOrganization->publish();

    actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->assertSee('Add to my notification list');

    actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('notification-list.add'), [
            'notificationable_type' => get_class($regulatedOrganization),
            'notificationable_id' => $regulatedOrganization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->assertSee('Remove from my notification list');

    actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('notification-list.remove'), [
            'notificationable_type' => get_class($regulatedOrganization),
            'notificationable_id' => $regulatedOrganization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('regulated-organizations.show', $regulatedOrganization));

    $user = $user->fresh();

    expect($user->regulatedOrganizationsForNotification)->toHaveCount(0);

    actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('notification-list.add'), [
            'notificationable_type' => get_class($regulatedOrganization),
            'notificationable_id' => $regulatedOrganization->id,
        ]);

    expect($regulatedOrganization->isNotifying($user))->toBeTrue();

    actingAs($user)->get(localized_route('notification-list.show'))
        ->assertSee('Umbrella Corporation');

    actingAs($user)->from(localized_route('notification-list.show'))
        ->post(localized_route('notification-list.remove'), [
            'notificationable_type' => get_class($regulatedOrganization),
            'notificationable_id' => $regulatedOrganization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('notification-list.show'));

    $user = $user->fresh();

    expect($user->regulatedOrganizationsForNotification)->toHaveCount(0);

    $nullUser = null;
    expect($regulatedOrganization->isNotifying($nullUser))->toBeFalse();
});

test('individual users can add and remove organizations from their notification list', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['name' => ['en' => 'Umbrella Corporation'], 'published_at' => now()]);

    actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('notification-list.add'), [
            'notificationable_type' => get_class($organization),
            'notificationable_id' => $organization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.show', $organization));

    expect($organization->isNotifying($user))->toBeTrue();

    actingAs($user)->get(localized_route('organizations.show', $organization))
        ->assertSee('Remove from my notification list');

    actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('notification-list.remove'), [
            'notificationable_type' => get_class($organization),
            'notificationable_id' => $organization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.show', $organization));

    $user = $user->fresh();

    expect($user->organizationsForNotification)->toHaveCount(0);

    actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('notification-list.add'), [
            'notificationable_type' => get_class($organization),
            'notificationable_id' => $organization->id,
        ]);

    actingAs($user)->get(localized_route('notification-list.show'))
        ->assertSee('Umbrella Corporation');

    actingAs($user)->from(localized_route('notification-list.show'))
        ->post(localized_route('notification-list.remove'), [
            'notificationable_type' => get_class($organization),
            'notificationable_id' => $organization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('notification-list.show'));

    $user = $user->fresh();

    expect($user->organizationsForNotification)->toHaveCount(0);

    $nullUser = null;
    expect($organization->isNotifying($nullUser))->toBeFalse();
});

test('add notificationable validation errors', function ($data, ?array $errors = null) {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['name' => ['en' => 'Umbrella Corporation'], 'published_at' => now()]);

    $baseData = [
        'notificationable_type' => get_class($organization),
        'notificationable_id' => $organization->id,
    ];

    $alternateData = [
        'notificationable_type' => User::class,
        'notificationable_id' => $user->id,
    ];

    $response = actingAs($user)->post(localized_route('notification-list.add'), array_merge($baseData, empty($data) ? $alternateData : $data));

    if (isset($errors)) {
        $response->assertSessionHasErrors($errors);
    } else {
        $response->assertForbidden();
    }
})->with('addNotificaitonableRequestValidationErrors');

test('remove notificationable validation errors', function ($data, array $errors) {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['name' => ['en' => 'Umbrella Corporation'], 'published_at' => now()]);

    $baseData = [
        'notificationable_type' => get_class($organization),
        'notificationable_id' => $organization->id,
    ];

    actingAs($user)->post(localized_route('notification-list.remove'), array_merge($baseData, $data))
        ->assertSessionHasErrors($errors);
})->with('removeNotificaitonableRequestValidationErrors');
