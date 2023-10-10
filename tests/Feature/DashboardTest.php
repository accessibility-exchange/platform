<?php

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;

test('individual user can access dashboard', function () {
    $user = User::factory()->create([
        'context' => 'individual',
    ]);

    $individual = $user->individual;
    $individual->roles = ['participant'];
    $individual->save();

    $response = $this->actingAs($user)->get(localized_route('dashboard'));
    $response->assertOk();
});

test('regulated organization user can access dashboard', function () {
    $regulatedOrganizationUser = User::factory()->create([
        'context' => 'regulated-organization',
    ]);

    RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('dashboard'));
    $response->assertOk();
});

test('organization user can access dashboard', function () {
    $organizationUser = User::factory()->create([
        'context' => 'organization',
    ]);

    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($organizationUser)->get(localized_route('dashboard'));

    $response->assertRedirect(localized_route('organizations.show-role-selection', $organization));

    $organization->roles = ['consultant'];
    $organization->save();

    $response = $this->actingAs($organizationUser->fresh())->get(localized_route('dashboard'));
    $response->assertOk();
});
