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

test('admin user dashboard prompts', function () {
    $user = User::factory()->create([
        'context' => UserContext::Administrator->value,
    ]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee(__('Customize this website’s accessibility'));

    $user->update(['dismissed_customize_prompt_at' => now()]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Customize this website’s accessibility'));
});

test('individual user dashboard propmts', function () {
    $user = User::factory()->create([
        'context' => UserContext::Individual->value,
    ]);

    $individual = $user->individual;
    $individual->roles = [IndividualRole::ConsultationParticipant->value];
    $individual->save();

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee(__('Customize this website’s accessibility'));

    $user->update(['dismissed_customize_prompt_at' => now()]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Customize this website’s accessibility'));
});

test('regulated organization user dashboard propmts', function () {
    $regulatedOrganizationUser = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
    ]);

    RegulatedOrganization::factory()
        ->hasAttached($regulatedOrganizationUser, ['role' => TeamRole::Administrator->value])
        ->create();

    actingAs($regulatedOrganizationUser)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee(__('Customize this website’s accessibility'))
        ->assertSee(__('Invite others to your organization'));

    $regulatedOrganizationUser->update(['dismissed_customize_prompt_at' => now()]);
    $regulatedOrganizationUser->regulatedOrganization->update(['dismissed_invite_prompt_at' => now()]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Customize this website’s accessibility'))
        ->assertDontSee(__('Invite others to your organization'));
});

test('organization user can dashboard propmts', function () {
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
        ->assertSee(__('Customize this website’s accessibility'))
        ->assertSee(__('Invite others to your organization'));

    $organizationUser->update(['dismissed_customize_prompt_at' => now()]);
    $organizationUser->organization->update(['dismissed_invite_prompt_at' => now()]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Customize this website’s accessibility'))
        ->assertDontSee(__('Invite others to your organization'));
});

test('training user dashboard propmts', function () {
    $user = User::factory()->create([
        'context' => UserContext::TrainingParticipant->value,
    ]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee(__('Customize this website’s accessibility'));

    $user->update(['dismissed_customize_prompt_at' => now()]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Customize this website’s accessibility'));
});
