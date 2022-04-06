<?php

use App\Models\CommunityMember;
use App\Models\Impact;
use App\Models\Sector;
use App\Models\User;

test('users can create community member pages', function () {
    $this->seed();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('community-members.create'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('community-members.create'), [
        'user_id' => $user->id,
        'name' => $user->name,
        'roles' => ['participant', 'consultant'],
    ]);

    $communityMember = CommunityMember::where('name', $user->name)->get()->first();

    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));

    $this->assertEquals($communityMember->user->id, $user->id);

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'locality' => 'Halifax',
        'region' => 'NS',
        'hide_location' => 1,
        'pronouns' => '',
        'bio' => '',
        'other_links' => [
            [
                'title' => 'My website',
                'url' => 'https://example.com',
            ],
        ],
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'publish' => __('Publish'),
    ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();
    $this->assertTrue($communityMember->checkStatus('published'));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'unpublish' => __('Unpublish'),
    ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();
    $this->assertFalse($communityMember->checkStatus('published'));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'preview' => __('Preview'),
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.show', ['communityMember' => $communityMember]));

    $response = $this->actingAs($user)
        ->from(localized_route('community-members.edit', $communityMember))
        ->put(localized_route('community-members.update', $communityMember), [
            'name' => $user->name,
            'locality' => 'Halifax',
            'region' => 'NS',
            'hide_location' => 1,
            'pronouns' => '',
            'bio' => '',
            'other_links' => [
                [
                    'title' => '',
                    'url' => '',
                ],
            ],
            'save_and_next' => __('Save and next'),
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-interests', $communityMember), [
        'sectors' => [Sector::pluck('id')->first()],
        'impacts' => [Impact::pluck('id')->first()],
        'areas_of_interest' => '',
        'save_and_previous' => __('Save and previous'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-experiences', $communityMember), [
        'lived_experiences' => [1],
        'age_group' => 'adult',
        'lived_experience' => '',
        'skills_and_strengths' => '',
        'work_and_volunteer_experiences' => [
            [
                'title' => 'Some job',
                'start_year' => '2021',
                'end_year' => '',
                'current' => 1,
            ],
        ],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-experiences', $communityMember), [
        'lived_experiences' => [1],
        'age_group' => 'adult',
        'lived_experience' => '',
        'skills_and_strengths' => '',
        'work_and_volunteer_experiences' => [
            [
                'title' => '',
                'start_year' => '',
                'end_year' => '',
            ],
        ],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-communication-preferences', $communityMember), [
        'email' => 'me@here.com',
        'phone' => '902-123-4567',
        'support_people' => [
            [
                'name' => '',
                'email' => '',
                'phone' => '',
            ],
        ],
        'preferred_contact_methods' => ['email'],
        'languages' => ['en'],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 5]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-communication-preferences', $communityMember), [
        'email' => 'me@here.com',
        'phone' => '902-123-4567',
        'support_people' => [
            [
                'name' => 'Someone',
                'email' => 'me@here.com',
                'phone' => '438-123-4567',
            ],
        ],
        'preferred_contact_methods' => ['email'],
        'languages' => ['en'],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 5]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-access-and-accomodations', $communityMember), [
        'meeting_types' => ['in_person', 'web_conference'],
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
});

test('entity users can not create community member pages', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);

    $response = $this->actingAs($user)->get(localized_route('community-members.create'));
    $response->assertForbidden();

    $response = $this->from(localized_route('community-members.create'))->post(localized_route('community-members.create'), [
        'user_id' => $user->id,
        'name' => $user->name,
        'bio' => 'Hi, welcome to my page.',
        'locality' => 'Halifax',
        'region' => 'NS',
    ]);

    $response->assertForbidden();
});

test('users can not create community member pages for other users', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('community-members.create'));
    $response->assertOk();

    $response = $this->actingAs($user)->post(localized_route('community-members.create'), [
        'user_id' => $other_user->id,
        'name' => $user->name,
        'bio' => 'Hi, welcome to my page.',
        'locality' => 'Halifax',
        'region' => 'NS',
    ]);

    $response->assertForbidden();
});

test('users can not create multiple community member pages', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('community-members.create'));
    $response->assertForbidden();

    $response = $this->actingAs($user)->post(localized_route('community-members.create'), [
        'user_id' => $user->id,
        'name' => $user->name,
        'bio' => 'Hi, welcome to my page.',
        'locality' => 'Halifax',
        'region' => 'NS',
    ]);

    $response->assertForbidden();
});

test('community member pages can be published and unpublished', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
        'publish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.show', $communityMember));
    $this->assertTrue($communityMember->checkStatus('published'));

    $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
        'unpublish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.show', $communityMember));

    $communityMember = $communityMember->fresh();

    $this->assertTrue($communityMember->checkStatus('draft'));
});

test('users can edit community member pages', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('community-members.edit', $communityMember));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $communityMember->name,
        'bio' => $communityMember->bio,
        'locality' => 'St John\'s',
        'region' => 'NL',
    ]);

    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));

    $draft_user = User::factory()->create();
    $draft_community_member = CommunityMember::factory()->create([
        'user_id' => $draft_user->id,
        'published_at' => null,
    ]);

    $response = $this->actingAs($draft_user)->get(localized_route('community-members.edit', $draft_community_member));
    $response->assertOk();

    $response = $this->actingAs($draft_user)->put(localized_route('community-members.update', $draft_community_member), [
        'name' => $draft_community_member->name,
        'bio' => $draft_community_member->bio,
        'locality' => 'St John\'s',
        'region' => 'NL',
        'creator' => $draft_community_member->creator,
    ]);

    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $draft_community_member, 'step' => 1]));
});

test('users can not edit others community member pages', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $communityMember = CommunityMember::factory()->create([
        'user_id' => $other_user->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('community-members.edit', $communityMember));
    $response->assertForbidden();

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $communityMember->name,
        'bio' => $communityMember->bio,
        'locality' => 'St John\'s',
        'region' => 'NL',
        'creator' => $communityMember->creator,
    ]);
    $response->assertForbidden();
});

test('users can delete community member pages', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('community-members.edit', $communityMember))->delete(localized_route('community-members.destroy', $communityMember), [
        'current_password' => 'password',
    ]);
    $response->assertRedirect(localized_route('dashboard'));
});

test('users can not delete community member pages with wrong password', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('community-members.edit', $communityMember))->delete(localized_route('community-members.destroy', $communityMember), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('community-members.edit', $communityMember));
});

test('users can not delete others community member pages', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $communityMember = CommunityMember::factory()->create([
        'user_id' => $other_user->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('community-members.edit', $communityMember))->delete(localized_route('community-members.destroy', $communityMember), [
        'current_password' => 'password',
    ]);
    $response->assertForbidden();
});

test('users can view own draft community member pages', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
        'published_at' => null,
    ]);

    $response = $this->actingAs($user)->get(localized_route('community-members.show', $communityMember));
    $response->assertOk();
});

test('users can not view others draft community member pages', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
        'published_at' => null,
    ]);

    $response = $this->actingAs($other_user)->get(localized_route('community-members.show', $communityMember));
    $response->assertForbidden();
});

test('users can view private sections of own community member pages', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(localized_route('community-members.show-experiences', $communityMember));
    $response->assertOk();
});

test('users can not view private sections of others community member pages', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($other_user)->get(localized_route('community-members.show-experiences', $communityMember));
    $response->assertForbidden();
});

test('guests can not view community member pages', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->get(localized_route('community-members.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('community-members.show', $communityMember));
    $response->assertRedirect(localized_route('login'));
});

test('community member pages can be published', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
        'published_at' => null,
    ]);

    $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
        'publish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.show', $communityMember));

    $communityMember = $communityMember->fresh();

    $this->assertTrue($communityMember->checkStatus('published'));
});

test('community member pages can be unpublished', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
        'unpublish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.show', $communityMember));

    $communityMember = $communityMember->fresh();

    $this->assertTrue($communityMember->checkStatus('draft'));
});

test('draft community members do not appear on community member index', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'published_at' => null,
    ]);

    $response = $this->actingAs($user)->get(localized_route('community-members.index'));
    $response->assertDontSee($communityMember->name);
});

test('published community members appear on community member index', function () {
    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('community-members.index'));
    $response->assertSee($communityMember->name);
});
