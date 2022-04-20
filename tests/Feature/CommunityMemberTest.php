<?php

use App\Models\AgeGroup;
use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\CommunityRole;
use App\Models\Engagement;
use App\Models\Impact;
use App\Models\LivedExperience;
use App\Models\Sector;
use App\Models\User;
use Database\Seeders\AgeGroupSeeder;
use Database\Seeders\CommunityRoleSeeder;
use Database\Seeders\CommunitySeeder;
use Database\Seeders\LivedExperienceSeeder;

test('community users can select a community role', function () {
    $this->seed(CommunityRoleSeeder::class);
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('community-members.show-role-selection'));
    $response->assertOk();

    $firstRole = CommunityRole::first();

    $response = $this->actingAs($user)
        ->from(localized_route('community-members.show-role-selection'))
        ->put(localized_route('community-members.save-role'), [
            'roles' => [$firstRole->id],
        ]);

    $response->assertRedirect(localized_route('dashboard'));

    $user = $user->fresh();
    expect($user->communityMember->communityRoles[0]->id)->toEqual($firstRole->id);
});

test('non-community members cannot select a community role', function () {
    $nonCommunityUser = User::factory()->create([
        'context' => 'regulated-organization',
    ]);

    $response = $this->actingAs($nonCommunityUser)->get(localized_route('community-members.show-role-selection'));
    $response->assertForbidden();
});

test('users can create community member pages', function () {
    $this->seed();

    $response = $this->withSession([
        'locale' => 'en',
        'signed_language' => 'ase',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => 'community-member',
    ])->post(localized_route('register-store'), [
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();

    $user = Auth::user();
    $communityMember = $user->communityMember;

    expect($communityMember)->toBeInstanceOf(CommunityMember::class);

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'locality' => 'Halifax',
        'region' => 'NS',
        'pronouns' => [],
        'bio' => ['en' => 'This is my bio.'],
        'working_languages' => [$user->locale],
        'links' => [
            'linkedin' => 'https://linkedin.com/in/someone',
            'twitter' => '',
            'instagram' => '',
            'facebook' => '',
        ],
        'other_links' => [
            [
                'title' => 'My website',
                'url' => 'https://example.com',
            ],
        ],
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();

    expect($communityMember->links)->toHaveKey('linkedin')->toHaveCount(1);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'working_languages' => $communityMember->working_languages,
        'publish' => __('Publish'),
    ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();
    $this->assertTrue($communityMember->checkStatus('published'));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'working_languages' => $communityMember->working_languages,
        'unpublish' => __('Unpublish'),
    ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();
    $this->assertFalse($communityMember->checkStatus('published'));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'working_languages' => $communityMember->working_languages,
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
            'pronouns' => '',
            'bio' => ['en' => 'This is my bio.'],
            'working_languages' => $communityMember->working_languages,
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
        'save_and_previous' => __('Save and previous'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-experiences', $communityMember), [
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
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-experiences', $communityMember), [
        'lived_experience' => '',
        'skills_and_strengths' => '',
        'relevant_experiences' => [
            [
                'title' => '',
                'start_year' => '',
                'end_year' => '',
            ],
        ],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-communication-and-meeting-preferences', $communityMember), [
        'email' => 'me@here.com',
        'phone' => '902-123-4567',
        'support_people' => [
            [
                'name' => '',
                'email' => '',
                'phone' => '',
            ],
        ],
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'me',
        'meeting_types' => ['in_person', 'web_conference'],
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update-communication-and-meeting-preferences', $communityMember), [
        'email' => 'me@here.com',
        'phone' => '902-123-4567',
        'support_people' => [
            [
                'name' => 'Someone',
                'email' => 'me@here.com',
                'phone' => '438-123-4567',
            ],
        ],
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'Someone',
        'meeting_types' => ['in_person', 'web_conference'],
        'save' => __('Save'),
    ]);

    $communityMember = $communityMember->fresh();

    expect($communityMember->phone)->toEqual('9021234567');

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]));
});

test('entity users can not create community member pages', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    expect($user->communityMember)->toBeNull();
});

test('community members with connector role must select connected identities', function () {
    $this->seed(CommunityRoleSeeder::class);
    $this->seed(LivedExperienceSeeder::class);
    $this->seed(CommunitySeeder::class);
    $this->seed(AgeGroupSeeder::class);

    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $connectorRole = CommunityRole::where('name->en', 'Community connector')->first();
    $livedExperience = LivedExperience::first();
    $community = Community::first();
    $ageGroup = AgeGroup::first();

    $communityMember->communityRoles()->sync([$connectorRole->id]);

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'working_languages' => [$user->locale],
    ]);

    $response->assertSessionHasErrors();

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'working_languages' => [$user->locale],
        'lived_experience_connections' => [$livedExperience->id],
        'community_connections' => [$community->id],
        'age_group_connections' => [$ageGroup->id],
    ]);

    $response->assertSessionHasNoErrors();

    $communityMember = $communityMember->fresh();

    expect($communityMember->livedExperienceConnections)->toHaveCount(1);
    expect($communityMember->communityConnections)->toHaveCount(1);
    expect($communityMember->ageGroupConnections)->toHaveCount(1);
});

test('users can edit community member pages', function () {
    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $response = $this->actingAs($user)->get(localized_route('community-members.edit', $communityMember));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $communityMember->name,
        'bio' => ['en' => $communityMember->bio],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'working_languages' => ['en'],
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));

    $draft_user = User::factory()->create();
    $draft_community_member = $draft_user->communityMember;

    $response = $this->actingAs($draft_user)->get(localized_route('community-members.edit', $draft_community_member));
    $response->assertOk();

    $response = $this->actingAs($draft_user)->put(localized_route('community-members.update', $draft_community_member), [
        'name' => $draft_community_member->name,
        'bio' => ['en' => $draft_community_member->bio],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'working_languages' => ['en'],
    ]);

    $response->assertSessionHasNoErrors();
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
        'working_languages' => ['en'],
    ]);
    $response->assertForbidden();
});

test('users can delete community member pages', function () {
    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $response = $this->actingAs($user)->from(localized_route('community-members.edit', $communityMember))->delete(localized_route('community-members.destroy', $communityMember), [
        'current_password' => 'password',
    ]);
    $response->assertRedirect(localized_route('dashboard'));
});

test('users can not delete community member pages with wrong password', function () {
    $user = User::factory()->create();
    $communityMember = $user->communityMember;

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

test('users can view their own draft community member pages', function () {
    $user = User::factory()->create();
    $communityMember = $user->communityMember;

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
    $communityMember = $user->communityMember;

    $response = $this->actingAs($user)->get(localized_route('community-members.show-experiences', $communityMember));
    $response->assertOk();
});

test('users can not view private sections of others community member pages', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $communityMember = $user->communityMember;

    $response = $this->actingAs($other_user)->get(localized_route('community-members.show-experiences', $communityMember));
    $response->assertForbidden();
});

test('guests can not view community member pages', function () {
    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $response = $this->get(localized_route('community-members.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('community-members.show', $communityMember));
    $response->assertRedirect(localized_route('login'));
});

test('community member pages can be published', function () {
    $user = User::factory()->create();
    $communityMember = $user->communityMember;

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
    $communityMember = $user->communityMember;

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

test('community members can participate in engagements', function () {
    $participant = CommunityMember::factory()->create();
    $engagement = Engagement::factory()->create();
    $engagement->participants()->attach($participant->id, ['status' => 'confirmed']);

    expect($participant->engagements)->toHaveCount(1);
});
