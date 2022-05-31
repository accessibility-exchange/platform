<?php

use App\Models\CommunityMember;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;

test('only individual users can have a block list', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('blocklist.show'));

    $response->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('blocklist.show'));

    $response->assertForbidden();
});

test('individual users can block and unblock regulated organizations', function () {
    $user = User::factory()->create();
    $regulatedOrganization = RegulatedOrganization::factory()->create(['name' => ['en' => 'Umbrella Corporation']]);
    $regulatedOrganization->publish();

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertSee('Block');

    $response = $this->actingAs($user)->from(localized_route('regulated-organizations.show', $regulatedOrganization))
        ->post(localized_route('blocklist.block'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $response = $this->actingAs($user)->get(localized_route('regulated-organizations.show', $regulatedOrganization));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('blocklist.show'));
    $response->assertSee('Umbrella Corporation');

    $response = $this->actingAs($user)->from(localized_route('blocklist.show'))
        ->post(localized_route('blocklist.unblock'), [
            'blockable_type' => get_class($regulatedOrganization),
            'blockable_id' => $regulatedOrganization->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('blocklist.show'));

    $user = $user->fresh();

    expect($user->blockedRegulatedOrganizations)->toHaveCount(0);
});

test('individual users can block and unblock organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['name' => ['en' => 'Umbrella Corporation']]);

    $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
    $response->assertSee('Block');

    $response = $this->actingAs($user)->from(localized_route('organizations.show', $organization))
        ->post(localized_route('blocklist.block'), [
            'blockable_type' => get_class($organization),
            'blockable_id' => $organization->id,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $response = $this->actingAs($user)->get(localized_route('organizations.show', $organization));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('blocklist.show'));
    $response->assertSee('Umbrella Corporation');

    $response = $this->actingAs($user)->from(localized_route('blocklist.show'))
        ->post(localized_route('blocklist.unblock'), [
            'blockable_type' => get_class($organization),
            'blockable_id' => $organization->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('blocklist.show'));

    $user = $user->fresh();

    expect($user->blockedOrganizations)->toHaveCount(0);
});

test('individual users can block and unblock community members', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('community-members.show', $communityMember));
    $response->assertSee('Block');

    $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))
        ->post(localized_route('blocklist.block'), [
            'blockable_type' => get_class($communityMember),
            'blockable_id' => $communityMember->id,
        ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $response = $this->actingAs($user)->get(localized_route('community-members.show', $communityMember));
    $response->assertForbidden();

    $response = $this->actingAs($user)->get(localized_route('blocklist.show'));
    $response->assertSee($communityMember->name);

    $response = $this->actingAs($user)->from(localized_route('blocklist.show'))
        ->post(localized_route('blocklist.unblock'), [
            'blockable_type' => get_class($communityMember),
            'blockable_id' => $communityMember->id,
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('blocklist.show'));

    $user = $user->fresh();

    expect($user->blockedIndividuals)->toHaveCount(0);
});
