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

test('community members can edit their roles', function () {
    $this->seed(CommunityRoleSeeder::class);
    $user = User::factory()->create();

    $communityMember = $user->communityMember;

    $participantRole = CommunityRole::where('name->en', 'Consultation participant')->first();
    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();
    $communityMember->communityRoles()->sync([$consultantRole->id]);
    $communityMember->publish();

    $communityMember = $communityMember->fresh();

    $response = $this->actingAs($user)
        ->get(localized_route('community-members.show-role-edit'));

    $response->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-' . $participantRole->id . '" value="' . $participantRole->id . '" aria-describedby="roles-' . $participantRole->id . '-hint"   />', false);
    $response->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-' . $consultantRole->id . '" value="' . $consultantRole->id . '" aria-describedby="roles-' . $consultantRole->id . '-hint" checked  />', false);

    $response = $this->actingAs($user)
        ->from(localized_route('community-members.show-role-edit'))
        ->put(localized_route('community-members.save-role'), [
            'roles' => [$participantRole->id],
        ]);

    $communityMember = $communityMember->fresh();

    expect($communityMember->checkStatus('published'))->toBeFalse();
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

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    expect($communityMember)->toBeInstanceOf(CommunityMember::class);

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'locality' => 'Halifax',
        'region' => 'NS',
        'pronouns' => [],
        'bio' => ['en' => 'This is my bio.'],
        'first_language' => $user->locale,
        'social_links' => [
            'linked_in' => 'https://linkedin.com/in/someone',
            'twitter' => '',
            'instagram' => '',
            'facebook' => '',
        ],
        'web_links' => [
            [
                'title' => 'My website',
                'url' => 'https://example.com',
            ],
        ],
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();

    expect($communityMember->social_links)->toHaveKey('linked_in')->toHaveCount(1);
    expect($communityMember->web_links)->toHaveCount(1);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'first_language' => $communityMember->first_language,
        'publish' => __('Publish'),
    ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();
    $this->assertTrue($communityMember->checkStatus('published'));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'first_language' => $communityMember->first_language,
        'unpublish' => __('Unpublish'),
    ]);

    $response->assertSessionHasNoErrors();
    $communityMember = $communityMember->fresh();
    $this->assertFalse($communityMember->checkStatus('published'));

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'first_language' => $communityMember->first_language,
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
            'first_language' => $communityMember->first_language,
            'web_links' => [
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
        'relevant_experiences' => [
            [
                'title' => '',
                'organization' => '',
                'start_year' => '',
                'end_year' => '',
                'current' => false,
            ],
        ],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]));

    $communityMember = $communityMember->fresh();

    expect($communityMember->relevant_experiences)->toHaveCount(0);

    $response = $this->actingAs($user)->put(localized_route('community-members.update-experiences', $communityMember), [
        'lived_experience' => '',
        'skills_and_strengths' => '',
        'relevant_experiences' => [
            [
                'title' => 'Some job',
                'organization' => 'Some place',
                'start_year' => '2021',
                'end_year' => '',
                'current' => 1,
            ],
        ],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]));

    $communityMember = $communityMember->fresh();

    expect($communityMember->relevant_experiences)->toHaveCount(1);

    $response = $this->actingAs($user)->put(localized_route('community-members.update-communication-and-meeting-preferences', $communityMember), [
        'email' => 'me@here.com',
        'phone' => '902-123-4567',
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
        'support_person_name' => 'Someone',
        'support_person_email' => 'me@here.com',
        'support_person_phone' => '438-123-4567',
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'support-person',
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
        'first_language' => $user->locale,
    ]);

    $response->assertSessionHasErrors();

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'first_language' => $user->locale,
        'lived_experience_connections' => [$livedExperience->id],
        'community_connections' => [$community->id],
        'age_group_connections' => [$ageGroup->id],
    ]);

    $response->assertSessionHasNoErrors();

    $communityMember = $communityMember->fresh();

    expect($communityMember->livedExperienceConnections)->toHaveCount(1);
    expect($communityMember->communityConnections)->toHaveCount(1);
    expect($communityMember->ageGroupConnections)->toHaveCount(1);
    expect($livedExperience->communityConnectors)->toHaveCount(1);
    expect($community->communityConnectors)->toHaveCount(1);
    expect($ageGroup->communityConnectors)->toHaveCount(1);
});

test('community members can have participant role', function () {
    $this->seed(CommunityRoleSeeder::class);

    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $participantRole = CommunityRole::where('name->en', 'Consultation participant')->first();
    $communityMember->communityRoles()->sync([$participantRole->id]);

    expect($communityMember->isParticipant())->toBeTrue();
});

test('community members can have consultant role', function () {
    $this->seed(CommunityRoleSeeder::class);

    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();
    $communityMember->communityRoles()->sync([$consultantRole->id]);

    expect($communityMember->isConsultant())->toBeTrue();
});

test('users can edit community member pages', function () {
    $this->seed(CommunityRoleSeeder::class);

    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($user)->get(localized_route('community-members.edit', $communityMember));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('community-members.update', $communityMember), [
        'name' => $communityMember->name,
        'bio' => ['en' => $communityMember->bio],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'first_language' => $communityMember->first_language,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 1]));

    $draftUser = User::factory()->create();
    $draftCommunityMember = $draftUser->communityMember;

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $draftCommunityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($draftUser)->get(localized_route('community-members.edit', $draftCommunityMember));
    $response->assertOk();

    $response = $this->actingAs($draftUser)->put(localized_route('community-members.update', $draftCommunityMember), [
        'name' => $draftCommunityMember->name,
        'bio' => ['en' => $draftCommunityMember->bio],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'first_language' => $communityMember->first_language,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.edit', ['communityMember' => $draftCommunityMember, 'step' => 1]));
});

test('users can not edit others community member pages', function () {
    $this->seed(CommunityRoleSeeder::class);

    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $communityMember = $user->communityMember;

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($otherUser)->get(localized_route('community-members.edit', $communityMember));
    $response->assertForbidden();

    $response = $this->actingAs($otherUser)->put(localized_route('community-members.update', $communityMember), [
        'name' => $communityMember->name,
        'bio' => $communityMember->bio,
        'locality' => 'St John\'s',
        'region' => 'NL',
        'first_language' => $communityMember->first_language,
    ]);
    $response->assertForbidden();
});

test('users can delete community member pages', function () {
    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $response = $this->actingAs($user)->delete(localized_route('community-members.destroy', $communityMember), [
        'current_password' => 'password',
    ]);
    $response->assertRedirect(localized_route('dashboard'));
});

test('users can not delete community member pages with wrong password', function () {
    $user = User::factory()->create();
    $communityMember = $user->communityMember;

    $response = $this->actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('community-members.destroy', $communityMember), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('dashboard'));
});

test('users can not delete others community member pages', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $communityMember = CommunityMember::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('community-members.destroy', $communityMember), [
        'current_password' => 'password',
    ]);
    $response->assertForbidden();
});

test('users can view their own draft community member pages', function () {
    $this->seed(CommunityRoleSeeder::class);

    $communityMember = CommunityMember::factory()->create(['published_at' => null]);

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($communityMember->user)->get(localized_route('community-members.show', $communityMember));
    $response->assertOk();
});

test('users can not view others draft community member pages', function () {
    $otherUser = User::factory()->create();
    $communityMember = CommunityMember::factory()->create(['published_at' => null]);

    $response = $this->actingAs($otherUser)->get(localized_route('community-members.show', $communityMember));
    $response->assertForbidden();
});

test('users can view community member pages', function () {
    $this->seed(CommunityRoleSeeder::class);

    $communityMember = CommunityMember::factory()->create();

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $communityMember->publish();
    $communityMember = $communityMember->fresh();

    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)->get(localized_route('community-members.show', $communityMember));
    $response->assertOk();
});

test('guests can not view community member pages', function () {
    $this->seed(CommunityRoleSeeder::class);

    $communityMember = CommunityMember::factory()->create();

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->get(localized_route('community-members.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('community-members.show', $communityMember));
    $response->assertRedirect(localized_route('login'));
});

test('community member pages can be published', function () {
    $this->seed(CommunityRoleSeeder::class);

    $communityMember = CommunityMember::factory()->create();

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($communityMember->user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
        'publish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.show', $communityMember));

    $communityMember = $communityMember->fresh();

    $this->assertTrue($communityMember->checkStatus('published'));
});

test('community member pages can be unpublished', function () {
    $this->seed(CommunityRoleSeeder::class);

    $communityMember = CommunityMember::factory()->create();

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($communityMember->user)->from(localized_route('community-members.show', $communityMember))->put(localized_route('community-members.update-publication-status', $communityMember), [
        'unpublish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('community-members.show', $communityMember));

    $communityMember = $communityMember->fresh();

    $this->assertTrue($communityMember->checkStatus('draft'));
});

test('draft community members do not appear on community member index', function () {
    $this->seed(CommunityRoleSeeder::class);

    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create([
        'published_at' => null,
    ]);

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($user)->get(localized_route('community-members.index'));
    $response->assertDontSee($communityMember->name);
});

test('published community members appear on community member index', function () {
    $this->seed(CommunityRoleSeeder::class);

    $user = User::factory()->create();
    $communityMember = CommunityMember::factory()->create();

    $consultantRole = CommunityRole::where('name->en', 'Accessibility consultant')->first();

    $communityMember->communityRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($user)->get(localized_route('community-members.index'));
    $response->assertSee($communityMember->name);
});

test('community members can participate in engagements', function () {
    $participant = CommunityMember::factory()->create();
    $engagement = Engagement::factory()->create();
    $engagement->participants()->attach($participant->id, ['status' => 'confirmed']);

    expect($participant->engagements)->toHaveCount(1);
});

test('community member\'s first name can be retrieved', function () {
    $communityMember = CommunityMember::factory()->create(['name' => 'Jonny Appleseed']);
    expect($communityMember->first_name)->toEqual('Jonny');
});

test('community member\'s contact person can be retrieved', function () {
    $communityMember = CommunityMember::factory()->create(['name' => 'Jonny Appleseed', 'preferred_contact_person' => 'me', 'support_person_name' => 'Jenny Appleseed']);

    expect($communityMember->contact_person)->toEqual('Jonny');

    $communityMember->update(['preferred_contact_person' => 'support_person']);

    expect($communityMember->contact_person)->toEqual('Jenny Appleseed');
});

test('community member\'s vrs requirement can be retrieved', function () {
    $communityMember = CommunityMember::factory()->create([
        'preferred_contact_person' => 'me',
        'vrs' => true,
        'support_person_vrs' => false,
    ]);

    expect($communityMember->requires_vrs)->toBeTrue();

    $communityMember->update(['preferred_contact_person' => 'support_person']);

    expect($communityMember->requires_vrs)->toBeFalse();
});

test('community member\'s primary contact point can be retrieved', function () {
    $communityMember = CommunityMember::factory()->create();

    expect($communityMember->primary_contact_point)->toBeNull();

    $communityMember->update([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
        'phone' => '9059999999',
        'vrs' => true,
        'preferred_contact_person' => 'me',
        'preferred_contact_method' =>  'email',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9051111111',
        'support_person_vrs' => false,
    ]);

    expect($communityMember->primary_contact_point)->toEqual('jonny@example.com');

    $communityMember->update(['preferred_contact_person' => 'support-person']);

    expect($communityMember->primary_contact_point)->toEqual('jenny@example.com');

    $communityMember->update(['preferred_contact_method' => 'phone']);

    expect($communityMember->primary_contact_point)->toEqual('9051111111');

    $communityMember->update(['preferred_contact_person' => 'me']);

    expect($communityMember->primary_contact_point)->toEqual("9059999999.  \nJonny requires VRS for phone calls");
});

test('community member\'s primary contact method can be retrieved', function () {
    $communityMember = CommunityMember::factory()->create();

    expect($communityMember->primary_contact_method)->toBeNull();

    $communityMember->update([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
        'phone' => '9059999999',
        'vrs' => true,
        'preferred_contact_person' => 'me',
        'preferred_contact_method' =>  'email',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9051111111',
        'support_person_vrs' => false,
    ]);

    expect($communityMember->primary_contact_method)->toEqual('Send an email to Jonny at [jonny@example.com](mailto:jonny@example.com).');

    $communityMember->update(['preferred_contact_person' => 'support-person']);

    expect($communityMember->primary_contact_method)->toEqual('Send an email to Jonny’s support person, Jenny Appleseed, at [jenny@example.com](mailto:jenny@example.com).');

    $communityMember->update(['preferred_contact_method' => 'phone']);

    expect($communityMember->primary_contact_method)->toEqual('Call Jonny’s support person, Jenny Appleseed, at 9051111111.');

    $communityMember->update(['preferred_contact_person' => 'me']);

    expect($communityMember->primary_contact_method)->toEqual("Call Jonny at 9059999999.  \nJonny requires VRS for phone calls.");
});

test('community member\'s alternate contact point can be retrieved', function () {
    $communityMember = CommunityMember::factory()->create();

    expect($communityMember->alternate_contact_point)->toBeNull();

    $communityMember->update([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
        'phone' => '9059999999',
        'vrs' => true,
        'preferred_contact_person' => 'me',
        'preferred_contact_method' =>  'phone',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9051111111',
        'support_person_vrs' => false,
    ]);

    expect($communityMember->alternate_contact_point)->toEqual('jonny@example.com');

    $communityMember->update(['preferred_contact_person' => 'support-person']);

    expect($communityMember->alternate_contact_point)->toEqual('jenny@example.com');

    $communityMember->update(['preferred_contact_method' => 'email']);

    expect($communityMember->alternate_contact_point)->toEqual('9051111111');

    $communityMember->update(['preferred_contact_person' => 'me']);

    expect($communityMember->alternate_contact_point)->toEqual("9059999999  \nJonny requires VRS for phone calls.");
});

test('community member\'s alternate contact method can be retrieved', function () {
    $communityMember = CommunityMember::factory()->create();

    expect($communityMember->alternate_contact_method)->toBeNull();

    $communityMember->update([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
        'phone' => '9059999999',
        'vrs' => true,
        'preferred_contact_person' => 'me',
        'preferred_contact_method' =>  'phone',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9051111111',
        'support_person_vrs' => false,
    ]);

    expect($communityMember->alternate_contact_method)->toEqual('[jonny@example.com](mailto:jonny@example.com)');

    $communityMember->update(['preferred_contact_person' => 'support-person']);

    expect($communityMember->alternate_contact_method)->toEqual('[jenny@example.com](mailto:jenny@example.com)');

    $communityMember->update(['preferred_contact_method' => 'email']);

    expect($communityMember->alternate_contact_method)->toEqual('9051111111');

    $communityMember->update(['preferred_contact_person' => 'me']);

    expect($communityMember->alternate_contact_method)->toEqual("9059999999  \nJonny requires VRS for phone calls.");
});

test('community member meeting type can be retrieved', function () {
    $communityMember = CommunityMember::factory()->create();
    expect($communityMember->getMeetingType('in_person'))->toEqual('In person');
    expect($communityMember->getMeetingType('phone'))->toEqual('Virtual – phone');
    expect($communityMember->getMeetingType('web_conference'))->toEqual('Virtual – web conference');
    expect($communityMember->getMeetingType('bad meeting'))->toBeNull();
});
