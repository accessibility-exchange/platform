<?php


use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;

test('users can access settings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.settings'));
    $response->assertOk();
});

test('guests can not access settings', function () {
    $response = $this->get(localized_route('users.settings'));
    $response->assertRedirect(localized_route('login'));
});

test('users can edit basic information', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.edit'));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('user-profile-information.update'), [
    'name' => 'Jonny Appleseed',
    'email' => $user->email,
    'locale' => $user->locale,
]);
    $response->assertRedirect(localized_route('users.edit'));

    $user = $user->fresh();
    $this->assertEquals($user->name, 'Jonny Appleseed');

    $response = $this->actingAs($user)->followingRedirects()->put(localized_route('user-profile-information.update'), [
    'name' => 'Jonny Appleseed',
    'email' => $user->email,
    'locale' => $user->locale,
]);
    $response->assertOk();
    $response->assertSee('Your information has been updated.');

    $response = $this->actingAs($user)->followingRedirects()->put(localized_route('user-profile-information.update'), [
    'name' => $user->name,
    'email' => 'me@example.net',
    'locale' => $user->locale,
]);
    $response->assertOk();
    $response->assertSee('Please verify your email address by clicking on the link we emailed to you.');

    $user = $user->fresh();
    $this->assertEquals($user->email, 'me@example.net');
    $this->assertNull($user->email_verified_at);
});

test('guests can not edit basic information', function () {
    $response = $this->get(localized_route('users.edit'));
    $response->assertRedirect(localized_route('login'));
});

test('users can edit roles and permissions', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.edit_roles_and_permissions'));
    $response->assertOk();
});

test('guests can not edit roles and permissions', function () {
    $response = $this->get(localized_route('users.edit_roles_and_permissions'));
    $response->assertRedirect(localized_route('login'));
});

test('users can edit display preferences', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.edit_display_preferences'));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('users.update_display_preferences'), [
    'theme' => 'system',
]);

    $response->assertRedirect(localized_route('users.edit_display_preferences'));
});

test('guests can not edit display preferences', function () {
    $response = $this->get(localized_route('users.edit_display_preferences'));
    $response->assertRedirect(localized_route('login'));
});

test('users can edit notification preferences', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.edit_notification_preferences'));
    $response->assertOk();
});

test('guests can not edit notification preferences', function () {
    $response = $this->get(localized_route('users.edit_notification_preferences'));
    $response->assertRedirect(localized_route('login'));
});

test('users can access my projects page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.show_my_projects'));
    $response->assertRedirect(localized_route('dashboard'));

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $regulatedOrganization = RegulatedOrganization::factory()
    ->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])
    ->create();
    $project = Project::factory()->create([
        'projectable_id' => $regulatedOrganization->id,
    ]);

    $this->assertEquals(1, count($regulatedOrganizationUser->projects()));

    $response = $this->actingAs($regulatedOrganizationUser)->get(localized_route('users.show_my_projects'));
    $response->assertOk();
});

test('guests can not access my projects page', function () {
    $response = $this->get(localized_route('users.show_my_projects'));
    $response->assertRedirect(localized_route('login'));
});

test('users can view the introduction', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.show-introduction'));

    $response->assertOk();
    $response->assertSee('Video for community members.');

    $user->update(['context' => 'organization']);

    $response = $this->actingAs($user)->get(localized_route('users.show-introduction'));

    $response->assertOk();
    $response->assertSee('Video for community organizations.');

    $user->update(['context' => 'regulated-organization']);

    $response = $this->actingAs($user)->get(localized_route('users.show-introduction'));

    $response->assertOk();
    $response->assertSee('Video for regulated organizations.');

    $user->update(['context' => 'regulated-organization-employee']);

    $response = $this->actingAs($user)->get(localized_route('users.show-introduction'));

    $response->assertOk();
    $response->assertSee('Video for regulated organization employees.');

    $response = $this->actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ]);

    $response->assertRedirect(localized_route('users.show-role-selection'));

    $user = $user->fresh();

    expect($user->finished_introduction)->toBeTrue();
});

test('users can select a role', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('users.show-role-selection'));

    $response->assertOk();
});
