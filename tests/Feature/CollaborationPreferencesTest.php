<?php

use App\Enums\UserContext;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('individual user can access collaboration preferences', function () {
    $user = User::factory()->create([
        'context' => UserContext::Individual->value,
    ]);

    $individual = $user->individual;
    $individual->roles = ['participant'];
    $individual->save();

    $response = $this->actingAs($user)->get(localized_route('dashboard.collaboration-preferences'));
    $response->assertOk();
    expect($response['individual'])->toBe($user->individual);
});

test('regulated organization user cannot access collaboration preferences', function () {
    $regulatedOrganizationUser = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
    ]);

    RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('dashboard.collaboration-preferences'));
    $response->assertForbidden();
});

test('organization user cannot access collaboration preferences', function () {
    $organizationUser = User::factory()->create([
        'context' => UserContext::Organization->value,
    ]);

    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => 'admin'])
        ->create();

    $response = $this->actingAs($organizationUser)->get(localized_route('dashboard.collaboration-preferences'));
    $response->assertRedirect(localized_route('organizations.show-role-selection', $organization));

    $organization->roles = ['consultant'];
    $organization->save();
    $organizationUser->refresh();

    $response = $this->actingAs($organizationUser)->get(localized_route('dashboard.collaboration-preferences'));
    $response->assertForbidden();
});

test('admin user cannot access collaboration preferences', function () {
    $adminUser = User::factory()->create([
        'context' => UserContext::Administrator->value,
    ]);

    $response = $this->actingAs($adminUser)->get(localized_route('dashboard.collaboration-preferences'));
    $response->assertForbidden();
});

test('training user cannot access collaboration preferences', function () {
    $trainingUser = User::factory()->create([
        'context' => UserContext::TrainingParticipant->value,
    ]);

    $response = $this->actingAs($trainingUser)->get(localized_route('dashboard.collaboration-preferences'));
    $response->assertForbidden();
});
