<?php

use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;

test('only individual users can have a block list', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('block-list.show'));

    $response->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('block-list.show'));

    $response->assertForbidden();
});

test('individual users can block and unblock regulated organizations', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => ['en' => 'Umbrella Corporation']]);
    $regulatedOrganization->publish();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertSee('Block');

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('block-list.show'));
    $response->assertSee('Umbrella Corporation');

    $response = $this->actingAs($user)->from(localized_route('block-list.show'))
        ->post(localized_route('block-list.unblock'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('block-list.show'));

    $user = $user->fresh();

    expect($user->blockedRegulatedOrganizations)->toHaveCount(0);
});

test('individual users can block and unblock organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['name' => ['en' => 'Umbrella Corporation']]);

    $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
    $response->assertSee('Block');

    $response = $this->actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($organization),
            'blockable_id' => $organization->id,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('block-list.show'));
    $response->assertSee('Umbrella Corporation');

    $response = $this->actingAs($user)->from(localized_route('block-list.show'))
        ->post(localized_route('block-list.unblock'), [
            'blockable_type' => get_class($organization),
            'blockable_id' => $organization->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('block-list.show'));

    $user = $user->fresh();

    expect($user->blockedOrganizations)->toHaveCount(0);
});

test('individual users can block and unblock individuals', function () {
    $user = User::factory()->create();
    $individual = Individual::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('individuals.show', $individual));
    $response->assertSee('Block');

    $response = $this->actingAs($user)->from(localized_route('individuals.show', $individual))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($individual),
            'blockable_id' => $individual->id,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $response = $this->actingAs($user)->get(localized_route('individuals.show', $individual));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('block-list.show'));
    $response->assertSee($individual->name);

    $response = $this->actingAs($user)->from(localized_route('block-list.show'))
        ->post(localized_route('block-list.unblock'), [
            'blockable_type' => get_class($individual),
            'blockable_id' => $individual->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('block-list.show'));

    $user = $user->fresh();

    expect($user->blockedIndividuals)->toHaveCount(0);
});

test('regulated organization member cannot block their regulated organization', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ]);
    $response->assertForbidden();
});

test('organization member cannot block their organization', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($organization),
            'blockable_id' => $organization->id,
        ]);
    $response->assertForbidden();
});

test('individual cannot block their individual profile', function () {
    $user = User::factory()->create();
    $individual = $user->individual;
    $response = $this->actingAs($user)->from(localized_route('individuals.show', $individual))
        ->post(localized_route('block-list.block'), [
            'blockable_type' => get_class($individual),
            'blockable_id' => $individual->id,
        ]);

    $response->assertForbidden();
});
