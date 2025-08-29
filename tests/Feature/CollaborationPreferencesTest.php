<?php

use App\Enums\OrganizationRole;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('individual user can access collaboration preferences', function () {
    $user = User::factory()->has(Individual::factory())->create();

    $response = actingAs($user)->get(localized_route('dashboard.collaboration-preferences'));
    $response->assertOk();

    expect($response['individual'])->toBe($user->individual);
});

test('regulated organization user cannot access collaboration preferences', function () {
    $regulatedOrganizationUser = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
    ]);

    RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => TeamRole::Administrator->value])
        ->create();

    actingAs($regulatedOrganizationUser)->get(localized_route('dashboard.collaboration-preferences'))
        ->assertForbidden();
});

test('organization user cannot access collaboration preferences', function () {
    $organizationUser = User::factory()->create([
        'context' => UserContext::Organization->value,
    ]);

    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => TeamRole::Administrator->value])
        ->create();

    actingAs($organizationUser)->get(localized_route('dashboard.collaboration-preferences'))
        ->assertRedirect(localized_route('organizations.show-role-selection', $organization));

    $organization->roles = [OrganizationRole::AccessibilityConsultant->value];
    $organization->save();
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('dashboard.collaboration-preferences'))
        ->assertForbidden();
});

test('admin user cannot access collaboration preferences', function () {
    $adminUser = User::factory()->create([
        'context' => UserContext::Administrator->value,
    ]);

    actingAs($adminUser)->get(localized_route('dashboard.collaboration-preferences'))
        ->assertForbidden();
});

test('training user cannot access collaboration preferences', function () {
    $trainingUser = User::factory()->create([
        'context' => UserContext::TrainingParticipant->value,
    ]);

    actingAs($trainingUser)->get(localized_route('dashboard.collaboration-preferences'))
        ->assertForbidden();
});
