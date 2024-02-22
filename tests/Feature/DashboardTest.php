<?php

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('individual user can access dashboard', function () {
    $user = User::factory()->create([
        'context' => 'individual',
    ]);

    $individual = $user->individual;
    $individual->roles = ['participant'];
    $individual->save();

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk();
});

test('regulated organization user can access dashboard', function () {
    $regulatedOrganizationUser = User::factory()->create([
        'context' => 'regulated-organization',
    ]);

    RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])
        ->create();

    actingAs($regulatedOrganizationUser)->get(localized_route('dashboard'))
        ->assertOk();
});

test('organization user can access dashboard', function () {
    $organizationUser = User::factory()->create([
        'context' => 'organization',
    ]);

    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => 'admin'])
        ->create();

    actingAs($organizationUser)->get(localized_route('dashboard'))
        ->assertRedirect(localized_route('organizations.show-role-selection', $organization));

    $organization->roles = ['consultant'];
    $organization->save();

    actingAs($organizationUser->fresh())->get(localized_route('dashboard'))
        ->assertOk();
});
