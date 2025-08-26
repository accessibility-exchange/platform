<?php

use App\Enums\OrganizationRole;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Models\Individual;
use App\Models\Invitation;
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
    $user = Individual::factory()->create()->user;

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee($user->name)
        ->assertDontSee(__('Watch introduction video again'));
});

test('individual user can see notification sent to them prior to their account creation', function () {
    $participantUserEmail = 'participant@example.com';
    $connectorUserEmail = 'connector@example.com';
    Invitation::factory()->create(['email' => $participantUserEmail, 'role' => 'participant']);
    Invitation::factory()->create(['email' => $connectorUserEmail, 'role' => 'connector']);

    $participantUser = User::factory()->create(['email' => $participantUserEmail, 'context' => UserContext::Individual->value]);
    $connectorUser = User::factory()->create(['email' => $connectorUserEmail, 'context' => UserContext::Individual->value]);

    actingAs($participantUser)->get(localized_route('dashboard'))
        ->assertSee(__('You have been invited as a Consultation Participant'));

    actingAs($connectorUser)->get(localized_route('dashboard'))
        ->assertSee(__('You have been invited as a Community Connector'));
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

    $organization->roles = [OrganizationRole::AccessibilityConsultant->value];
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

    $user->prompts->dismissed_customize_prompt_at = now();
    $user->save();

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Customize this website’s accessibility'));
});

test('individual user dashboard propmts', function () {
    $user = Individual::factory()->create()->user;

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee(__('Customize this website’s accessibility'));

    $user->prompts->dismissed_customize_prompt_at = now();
    $user->save();

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

    $regulatedOrganizationUser->prompts->dismissed_customize_prompt_at = now();
    $regulatedOrganizationUser->regulatedOrganization->prompts->dismissed_invite_prompt_at = now();
    $regulatedOrganizationUser->save();
    $regulatedOrganizationUser->regulatedOrganization->save();
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

    $organization->roles = [OrganizationRole::AccessibilityConsultant->value];
    $organization->save();

    actingAs($organizationUser->fresh())->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee(__('Customize this website’s accessibility'))
        ->assertSee(__('Invite others to your organization'));

    $organizationUser->prompts->dismissed_customize_prompt_at = now();
    $organizationUser->organization->prompts->dismissed_invite_prompt_at = now();
    $organizationUser->save();
    $organizationUser->organization->save();
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

    $user->prompts->dismissed_customize_prompt_at = now();
    $user->save();

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Customize this website’s accessibility'));
});
