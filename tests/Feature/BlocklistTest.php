<?php

use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Support\Facades\Config;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Config::set('app.features.blocking', true);
});

test('only individual users can have a block list', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('block-list.show'))
        ->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);

    actingAs($regulatedOrganizationUser)->get(localized_route('block-list.show'))
        ->assertForbidden();
});

test('individual users can block and unblock regulated organizations', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => ['en' => 'Umbrella Corporation'], 'published_at' => now()]);

    actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->assertSee('Block');

    actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect($regulatedOrganization->blockedBy($user))->toBeTrue();

    actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->assertForbidden();

    actingAs($user)->get(localized_route('block-list.show'))
        ->assertSee('Umbrella Corporation');

    actingAs($user)->from(localized_route('block-list.show'))
        ->post(localized_route('block-list.unblock'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('block-list.show'));

    $user = $user->fresh();

    expect($user->blockedRegulatedOrganizations)->toHaveCount(0);

    $nullUser = null;
    expect($regulatedOrganization->blockedBy($nullUser))->toBeFalse();
});

test('individual users can block and unblock organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['name' => ['en' => 'Umbrella Corporation'], 'published_at' => now()]);

    actingAs($user)->get(localized_route('organizations.show', $organization))
        ->assertSee('Block');

    actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($organization),
            'blockable_id' => $organization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect($organization->blockedBy($user))->toBeTrue();

    actingAs($user)->get(localized_route('organizations.show', $organization))
        ->assertForbidden();

    actingAs($user)->get(localized_route('block-list.show'))
        ->assertSee('Umbrella Corporation');

    actingAs($user)->from(localized_route('block-list.show'))
        ->post(localized_route('block-list.unblock'), [
            'blockable_type' => get_class($organization),
            'blockable_id' => $organization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('block-list.show'));

    $user = $user->fresh();

    expect($user->blockedOrganizations)->toHaveCount(0);

    $nullUser = null;
    expect($organization->blockedBy($nullUser))->toBeFalse();
});

test('individual users can block and unblock individuals', function () {
    $user = User::factory()->create();
    $individual = Individual::factory()->create(['roles' => ['consultant'], 'consulting_services' => ['analysis']]);

    actingAs($user)->get(localized_route('individuals.show', $individual))
        ->assertSee('Block');

    actingAs($user)->from(localized_route('individuals.show', $individual))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($individual),
            'blockable_id' => $individual->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect($individual->blockedBy($user))->toBeTrue();

    actingAs($user)->get(localized_route('individuals.show', $individual))
        ->assertForbidden();

    actingAs($user)->get(localized_route('block-list.show'))
        ->assertSee($individual->name);

    actingAs($user)->from(localized_route('block-list.show'))
        ->post(localized_route('block-list.unblock'), [
            'blockable_type' => get_class($individual),
            'blockable_id' => $individual->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('block-list.show'));

    $user = $user->fresh();

    expect($user->blockedIndividuals)->toHaveCount(0);

    $nullUser = null;
    expect($individual->blockedBy($nullUser))->toBeFalse();
});

test('regulated organization member cannot block their regulated organization', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ])
        ->assertForbidden();
});

test('organization member cannot block their organization', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($organization),
            'blockable_id' => $organization->id,
        ])
        ->assertForbidden();
});

test('individual cannot block their individual profile', function () {
    $user = User::factory()->create();
    $individual = $user->individual;
    actingAs($user)->from(localized_route('individuals.show', $individual))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($individual),
            'blockable_id' => $individual->id,
        ])
        ->assertForbidden();
});

test('individual warning when attempt to block again', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => ['en' => 'Umbrella Corporation'], 'published_at' => now()]);

    actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->assertSee('Block');

    actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect($regulatedOrganization->blockedBy($user))->toBeTrue();

    actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect($regulatedOrganization->blockedBy($user))->toBeTrue();

    expect(flash()->class)->toBe('warning|Already on your block list');
    expect(flash()->message)->toBe(__(':blockable is already on your block list.', ['blockable' => $regulatedOrganization->name]));
});

test('individual warning when unblocking user not on block list', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => ['en' => 'Umbrella Corporation'], 'published_at' => now()]);

    actingAs($user)->from(localized_route('block-list.show'))
        ->post(localized_route('block-list.unblock'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect(flash()->class)->toBe('warning|Could not be blocked because it was not on your block list.');
    expect(flash()->message)->toBe(__(':blockable could not be unblocked because it was not on your block list.', ['blockable' => $regulatedOrganization->name]));
});
