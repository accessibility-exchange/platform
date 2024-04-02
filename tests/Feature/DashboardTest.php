<?php

use App\Enums\IndividualRole;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('admin user can access dashboard', function () {
    $user = User::factory()->create([
        'context' => UserContext::Administrator->value,
    ]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee($user->name)
        ->assertDontSee(__('Watch introduction video again'));
});

test('individual user can access dashboard', function () {
    $user = User::factory()->create([
        'context' => UserContext::Individual->value,
    ]);

    $individual = $user->individual;
    $individual->roles = [IndividualRole::ConsultationParticipant->value];
    $individual->save();

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee($user->name)
        ->assertSee(__('Watch introduction video again'));
});

test('regulated organization user can access dashboard', function () {
    $regulatedOrganizationUser = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
    ]);

    RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => TeamRole::Administrator->value])
        ->create();

    actingAs($regulatedOrganizationUser)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee($regulatedOrganizationUser->name)
        ->assertSee(__('Watch introduction video again'));
});

test('organization user can access dashboard', function () {
    $organizationUser = User::factory()->create([
        'context' => UserContext::Organization->value,
    ]);

    $organization = Organization::factory()
        ->hasAttached($organizationUser, ['role' => TeamRole::Administrator->value])
        ->create();

    actingAs($organizationUser)->get(localized_route('dashboard'))
        ->assertRedirect(localized_route('organizations.show-role-selection', $organization));

    $organization->roles = ['consultant'];
    $organization->save();

    actingAs($organizationUser->fresh())->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee($organizationUser->name)
        ->assertSee(__('Watch introduction video again'));
});

test('training user can access dashboard', function () {
    $user = User::factory()->create([
        'context' => UserContext::TrainingParticipant->value,
    ]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee($user->name)
        ->assertDontSee(__('Watch introduction video again'));
});
