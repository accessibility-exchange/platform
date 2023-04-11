<?php

use App\Enums\CommunityConnectorHasLivedExperience;
use App\Enums\EngagementFormat;
use App\Enums\IdentityCluster;
use App\Enums\MeetingType;
use App\Http\Requests\UpdateIndividualConstituenciesRequest;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\Scopes\ReachableIdentityScope;
use App\Models\Sector;
use App\Models\User;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;

beforeEach(function () {
    $this->seed(IdentitySeeder::class);

    $this->livedExperience = Identity::withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::LivedExperience)->first();
    $this->areaType = Identity::whereJsonContains('clusters', IdentityCluster::Area)->first();
});

test('individual users can select an individual role', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('dashboard'));
    $response->assertRedirect(localized_route('individuals.show-role-selection'));

    $response = $this->actingAs($user)->get(localized_route('individuals.show-role-selection'));
    $response->assertOk();

    $response = $this->actingAs($user)
        ->followingRedirects()
        ->from(localized_route('individuals.show-role-selection'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => ['participant', 'consultant'],
        ]);

    $response->assertSee('Your roles have been saved.');

    $user = $user->fresh();
    expect($user->individual->isParticipant())->toBeTrue();
});

test('non-individuals cannot select an individual role', function () {
    $nonCommunityUser = User::factory()->create([
        'context' => 'regulated-organization',
    ]);

    $response = $this->actingAs($nonCommunityUser)->get(localized_route('individuals.show-role-selection'));
    $response->assertForbidden();
});

test('individuals can edit their roles', function () {
    $user = User::factory()->create();

    $individual = $user->individual;
    $individual->roles = ['consultant', 'connector'];
    $individual->save();
    $individual->publish();

    $individual = $individual->fresh();

    $response = $this->actingAs($user)
        ->get(localized_route('individuals.show-role-edit'));

    $response->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-participant" value="participant" aria-describedby="roles-participant-hint"   />', false);
    $response->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-consultant" value="consultant" aria-describedby="roles-consultant-hint" checked  />', false);

    $response = $this->actingAs($user)
        ->followingRedirects()
        ->from(localized_route('individuals.show-role-edit'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => ['participant'],
        ]);

    $response->assertSee('Your roles have been saved.');

    $individual = $individual->fresh();

    expect($individual->isPreviewable())->toBeFalse();
    expect($individual->isPublishable())->toBeFalse();
    expect($individual->checkStatus('published'))->toBeFalse();

    $response = $this->actingAs($user)
        ->followingRedirects()
        ->from(localized_route('individuals.show-role-edit'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => ['consultant'],
        ]);

    $response->assertSee('Your roles have been saved. Please review your page.');

    $individual = $individual->fresh();

    $response = $this->actingAs($user)
        ->followingRedirects()
        ->from(localized_route('individuals.show-role-edit'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => ['consultant', 'participant'],
        ]);

    $response->assertDontSee('Your roles have been saved. Please review your page.');
    $response->assertSee('Your roles have been saved.');
});

test('flash message after individual role change', function ($initialRoles, $newRoles, $expected) {
    $user = User::factory()->create();
    $individual = $user->individual;

    $individual->fill([
        'roles' => $initialRoles,
    ]);
    $individual->save();
    $individual->refresh();

    $response = $this->actingAs($individual->user)
        ->put(localized_route('individuals.save-roles'), [
            'roles' => $newRoles,
        ]);
    $response->assertSessionHasNoErrors();

    expect(flash()->class)->toBe($expected['class']);
    expect(flash()->message)->toBe($expected['message']($individual));
})->with('individualRoleChange');

test('users can create individual pages', function () {
    $this->seed(ImpactSeeder::class);
    $this->seed(SectorSeeder::class);

    $response = $this->withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => 'individual',
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ]);

    $this->assertAuthenticated();

    $user = Auth::user();
    $user->update(['oriented_at' => now()]);

    $user = $user->fresh();
    $individual = $user->individual;

    $individual->fill([
        'roles' => ['consultant'],
        'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
        'meeting_types' => [MeetingType::InPerson->value],
    ]);
    $individual->save();

    $individual->identityConnections()->attach($this->livedExperience->id);
    $individual->identityConnections()->attach($this->areaType->id);

    expect($individual)->toBeInstanceOf(Individual::class);

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'locality' => 'Halifax',
        'region' => 'NS',
        'pronouns' => [],
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            'designing-consultation',
            'running-consultation',
        ],
        'social_links' => [
            'linked_in' => 'https://linkedin.com/in/someone',
            'twitter' => '',
            'instagram' => '',
            'facebook' => '',
        ],
        'website_links' => 'https://example.com',
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
    $individual = $individual->fresh();

    expect($individual->social_links)->toHaveKey('linked_in')->toHaveCount(1);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 1]));

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            'designing-consultation',
            'running-consultation',
        ],
        'publish' => __('Publish'),
    ]);

    $response->assertSessionHasNoErrors();
    $individual = $individual->fresh();
    $this->assertTrue($individual->checkStatus('published'));

    $response = $this->actingAs($user)->followingRedirects()->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            'designing-consultation',
            'running-consultation',
        ],
        'save' => __('Save'),
    ]);
    $response->assertSee('You have successfully saved your individual page.');

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            'designing-consultation',
            'running-consultation',
        ],
        'unpublish' => __('Unpublish'),
    ]);
    $response->assertSessionHasNoErrors();
    $individual = $individual->fresh();
    $this->assertFalse($individual->checkStatus('published'));

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            'designing-consultation',
            'running-consultation',
        ],
        'preview' => __('Preview'),
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.show', ['individual' => $individual]));

    $response = $this->actingAs($user)
        ->from(localized_route('individuals.edit', $individual))
        ->put(localized_route('individuals.update', $individual), [
            'name' => $user->name,
            'locality' => 'Halifax',
            'region' => 'NS',
            'pronouns' => '',
            'bio' => ['en' => 'This is my bio.'],
            'consulting_services' => [
                'designing-consultation',
                'running-consultation',
            ],
            'save_and_next' => __('Save and next'),
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 2]));

    $response = $this->actingAs($user)->put(localized_route('individuals.update-interests', $individual), [
        'sectors' => [Sector::pluck('id')->first()],
        'impacts' => [Impact::pluck('id')->first()],
        'save_and_previous' => __('Save and previous'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 2]));

    $response = $this->actingAs($user)->put(localized_route('individuals.update-experiences', $individual), [
        'lived_experience' => '',
        'skills_and_strengths' => '',
        'relevant_experiences' => [
            [
                'title' => 'First job',
                'organization' => 'First place',
                'start_year' => '2021',
                'end_year' => '',
                'current' => 1,
            ],
        ],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 3]));

    $response = $this->actingAs($user)->put(localized_route('individuals.update-experiences', $individual), [
        'lived_experience' => '',
        'skills_and_strengths' => '',
        'relevant_experiences' => [],
        'save_and_next' => __('Save and next'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 3]));

    $individual = $individual->fresh();

    expect($individual->relevant_experiences)->toHaveCount(0);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-experiences', $individual), [
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
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 3]));

    $individual = $individual->fresh();

    expect($individual->relevant_experiences)->toHaveCount(1);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), [
        'email' => 'me@here.com',
        'phone' => '902-444-4567',
        'vrs' => true,
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'me',
        'meeting_types' => ['in_person', 'web_conference'],
        'save' => __('Save'),
    ]);

    $individual->refresh();
    expect($individual->user->vrs)->toBeTrue();

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));

    $response = $this->actingAs($user)->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), [
        'email' => 'me@here.com',
        'phone' => '902-444-4567',
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'me',
        'meeting_types' => ['in_person', 'web_conference'],
        'save' => __('Save'),
    ]);

    $individual->refresh();
    expect($individual->user->vrs)->toBeNull();

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));

    $response = $this->actingAs($user)->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), [
        'email' => 'me@here.com',
        'phone' => '902-444-4567',
        'support_person_name' => 'Someone',
        'support_person_email' => 'me@here.com',
        'support_person_phone' => '438-444-4567',
        'support_person_vrs' => true,
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'support-person',
        'meeting_types' => ['in_person', 'web_conference'],
        'save' => __('Save'),
    ]);

    $individual = $individual->fresh();

    expect($individual->user->phone)->toEqual('');
    expect($individual->user->support_person_phone)->toEqual('+14384444567');
    expect($individual->user->support_person_vrs)->toBeTrue();

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));

    $response = $this->actingAs($user)->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), [
        'email' => 'me@here.com',
        'phone' => '902-444-4567',
        'support_person_name' => 'Someone',
        'support_person_email' => 'me@here.com',
        'support_person_phone' => '438-444-4567',
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'support-person',
        'meeting_types' => ['in_person', 'web_conference'],
        'save' => __('Save'),
    ]);

    $individual = $individual->fresh();

    expect($individual->user->phone)->toEqual('');
    expect($individual->user->support_person_vrs)->toBeNull();

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));
});

test('entity users can not create individual pages', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    expect($user->individual)->toBeNull();
});

test('individuals with connector role can represent individuals with disabilities', function () {
    $user = User::factory()->create();
    $individual = $user->individual;
    $individual->roles = ['connector'];
    $individual->save();

    expect($individual->base_disability_type)->toEqual('');
    expect($individual->hasConnections('disabilityAndDeafConnections'))->toBeNull();

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), []);

    $response->assertSessionHasErrors();

    $disabilityOrDeafIdentity = Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first();

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$this->livedExperience->id],
        'area_type_connections' => [$this->areaType->id],
        'disability_and_deaf_connections' => [$disabilityOrDeafIdentity->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->livedExperienceConnections)->toHaveCount(1);
    expect($individual->base_disability_type)->toEqual('specific_disabilities');
    expect($individual->hasConnections('genderDiverseConnections'))->toBeFalse();
    expect($individual->hasConnections('disabilityAndDeafConnections'))->toBeTrue();
    expect($individual->disabilityAndDeafConnections)->toHaveCount(1);
    expect($this->livedExperience->communityConnectors)->toHaveCount(1);
    expect($individual->other_disability_connection)->toEqual('Something not listed');

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$this->livedExperience->id],
        'disability_and_deaf' => false,
        'base_disability_type' => null,
        'area_type_connections' => [$this->areaType->id],
        'has_other_disability_connection' => null,
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $individual->refresh();

    expect($individual->extra_attributes->get('disability_and_deaf_connections'))->toBeNull();
    expect($individual->other_disability_connection)->toBeEmpty();
});

test('individuals with connector role can represent cross-disability individuals', function () {
    $user = User::factory()->create();
    $individual = $user->individual;
    $individual->roles = ['connector'];
    $individual->save();

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$this->livedExperience->id],
        'base_disability_type' => 'cross_disability_and_deaf',
        'area_type_connections' => [$this->areaType->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual->refresh();

    expect($individual->base_disability_type)->toEqual('cross_disability_and_deaf');

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$this->livedExperience->id],
        'disability_and_deaf' => false,
        'base_disability_type' => null,
        'area_type_connections' => [$this->areaType->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $individual->refresh();

    expect($individual->extra_attributes->get('cross_disability_and_deaf_connections'))->toBeNull();
});

test('individuals with connector role can represent individuals in specific age brackets', function () {
    $user = User::factory()->create();
    $individual = $user->individual;
    $individual->roles = ['connector'];
    $individual->save();

    $ageBracket = Identity::whereJsonContains('clusters', IdentityCluster::Age)->first();

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$this->livedExperience->id],
        'area_type_connections' => [$this->areaType->id],
        'has_age_bracket_connections' => 1,
        'age_bracket_connections' => [$ageBracket->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->ageBracketConnections)->toHaveCount(1);
    expect($ageBracket->communityConnectors)->toHaveCount(1);
});

test('individuals with connector role can represent refugees and immigrants', function () {
    $user = User::factory()->create();
    $individual = $user->individual;
    $individual->roles = ['connector'];
    $individual->save();

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$this->livedExperience->id],
        'area_type_connections' => [$this->areaType->id],
        'refugees_and_immigrants' => 1,
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->statusConnections)->toHaveCount(2);
});

test('individuals with connector role can represent gender and sexual minorities', function () {
    $user = User::factory()->create();
    $individual = $user->individual;
    $individual->roles = ['connector'];
    $individual->save();

    $genderAndSexualIdentities = array_merge(Identity::whereJsonContains('clusters', IdentityCluster::Gender)->whereNot(function ($query) {
        $query->whereJsonContains('clusters', IdentityCluster::GenderDiverse);
    })->pluck('id')->toArray(),
        Identity::whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->whereNot(function ($query) {
            $query->whereJsonContains('clusters', IdentityCluster::Gender);
        })->pluck('id')->toArray());

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$this->livedExperience->id],
        'area_type_connections' => [$this->areaType->id],
        'has_gender_and_sexuality_connections' => 1,
        'nb_gnc_fluid_identity' => 1,
        'gender_and_sexuality_connections' => $genderAndSexualIdentities,
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);
    $response->assertSessionHasNoErrors();

    $individual->refresh();

    expect($individual->genderIdentityConnections)->toHaveCount(4);
    expect($individual->genderAndSexualityConnections)->toHaveCount(6);
    expect($individual->hasConnections('genderDiverseConnections'))->toBeTrue();
});

test('individuals with connector role can represent ethnoracial identities', function () {
    $user = User::factory()->create();
    $individual = $user->individual;
    $individual->roles = ['connector'];
    $individual->save();

    $ethnoracialIdentity = Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->first();

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$this->livedExperience->id],
        'ethnoracial_identity_connections' => [$ethnoracialIdentity->id],
        'area_type_connections' => [$this->areaType->id],
    ]);

    unset($data['has_other_ethnoracial_identity_connection']);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->ethnoracialIdentityConnections)->toHaveCount(1);
    expect($individual->other_ethnoracial_identity_connections)->toBeNull();
});

test('individuals can have participant role', function () {
    $user = User::factory()->create();
    $individual = $user->individual;

    $individual->roles = ['participant'];
    $individual->save();

    expect($individual->fresh()->isParticipant())->toBeTrue();
});

test('individuals can have consultant role', function () {
    $user = User::factory()->create();
    $individual = $user->individual;

    $individual->roles = ['consultant'];
    $individual->save();

    expect($individual->fresh()->isConsultant())->toBeTrue();
});

test('users can edit individual pages', function () {
    $user = User::factory()->create();
    $individual = $user->individual;

    expect($individual->isPublishable())->toBeFalse();

    $individual->roles = ['participant'];
    $individual->save();

    $response = $this->actingAs($user)->get(localized_route('individuals.edit', $individual));
    $response->assertNotFound();

    $individual->roles = ['consultant'];
    $individual->save();

    $response = $this->actingAs($user)->get(localized_route('individuals.edit', $individual));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $individual->name,
        'bio' => ['en' => 'test bio'],
        'consulting_services' => [
            'designing-consultation',
            'running-consultation',
        ],
        'locality' => 'St John\'s',
        'region' => 'NL',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 1]));

    $draftUser = User::factory()->create();
    $draftIndividual = $draftUser->individual;

    $draftIndividual->roles = ['consultant'];
    $draftIndividual->save();

    $response = $this->actingAs($draftUser)->get(localized_route('individuals.edit', $draftIndividual));
    $response->assertOk();

    $response = $this->actingAs($draftUser)->put(localized_route('individuals.update', $draftIndividual), [
        'name' => $draftIndividual->name,
        'bio' => ['en' => 'draft bio'],
        'consulting_services' => [
            'designing-consultation',
            'running-consultation',
        ],
        'locality' => 'St John\'s',
        'region' => 'NL',
        'working_languages' => [''],
    ]);

    $draftIndividual = $draftIndividual->fresh();

    expect($draftIndividual->working_languages)->toBeEmpty();

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $draftIndividual, 'step' => 1]));
});

test('users can not edit others individual pages', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $individual = $user->individual;
    $individual->roles = ['consultant'];
    $individual->save();

    $response = $this->actingAs($otherUser)->get(localized_route('individuals.edit', $individual));
    $response->assertForbidden();

    $response = $this->actingAs($otherUser)->put(localized_route('individuals.update', $individual), [
        'name' => $individual->name,
        'bio' => $individual->bio,
        'locality' => 'St John\'s',
        'region' => 'NL',
    ]);
    $response->assertForbidden();
});

test('users can delete individual pages', function () {
    $user = User::factory()->create();
    $individual = $user->individual;

    $response = $this->actingAs($user)->delete(localized_route('individuals.destroy', $individual), [
        'current_password' => 'password',
    ]);
    $response->assertRedirect(localized_route('dashboard'));
});

test('users can not delete individual pages with wrong password', function () {
    $user = User::factory()->create();
    $individual = $user->individual;

    $response = $this->actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('individuals.destroy', $individual), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('dashboard'));
});

test('users can not delete others individual pages', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $individual = Individual::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('individuals.destroy', $individual), [
        'current_password' => 'password',
    ]);
    $response->assertForbidden();
});

test('users can view their own draft individual pages', function () {
    $individual = Individual::factory()->create([
        'published_at' => null,
        'consulting_services' => ['analysis'],
        'roles' => ['consultant'],
        'extra_attributes' => [
            'has_age_brackets' => true,
            'has_ethnoracial_identities' => true,
            'has_gender_and_sexual_identities' => true,
            'has_indigenous_identities' => true,
        ],
        'meeting_types' => ['in_person'],
        'bio' => ['en' => 'ok'],
    ]);

    $response = $this->actingAs($individual->user)->get(localized_route('individuals.show', $individual));
    $response->assertOk();
});

test('users can not view others draft individual pages', function () {
    $otherUser = User::factory()->create();

    $individual = Individual::factory()->create(['published_at' => null, 'roles' => ['consultant']]);

    $response = $this->actingAs($otherUser)->get(localized_route('individuals.show', $individual));
    $response->assertNotFound();
});

test('users can view individual pages', function () {
    $individual = Individual::factory()->create([
        'consulting_services' => ['analysis'],
        'roles' => ['consultant'],
    ]);

    $individual->publish();
    $individual = $individual->fresh();

    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)->get(localized_route('individuals.show', $individual));
    $response->assertOk();
});

test('users can not view individual pages if the individual is not a consultant or connector', function () {
    $individual = Individual::factory()->create([
        'roles' => ['participant'],
    ]);

    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)->get(localized_route('individuals.show', $individual));
    $response->assertNotFound();
});

test('users without a verified email can not view individual pages', function () {
    $individual = Individual::factory()->create([
        'consulting_services' => ['analysis'],
        'roles' => ['consultant'],
    ]);

    $individual->publish();
    $individual = $individual->fresh();

    $user = User::factory()->create(['email_verified_at' => null]);

    $response = $this->actingAs($user)->get(localized_route('individuals.index'));
    $response->assertRedirect(localized_route('verification.notice'));

    $response = $this->actingAs($user)->get(localized_route('individuals.show', $individual));
    $response->assertRedirect(localized_route('verification.notice'));
});

test('guests can not view individual pages', function () {
    $individual = Individual::factory()->create(['roles' => ['consultant']]);

    $response = $this->get(localized_route('individuals.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('individuals.show', $individual));
    $response->assertRedirect(localized_route('login'));
});

test('individual pages can be published', function () {
    $individual = Individual::factory()->create(['roles' => ['consultant']]);

    $response = $this->actingAs($individual->user)->from(localized_route('individuals.show', $individual))->put(localized_route('individuals.update-publication-status', $individual), [
        'publish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.show', $individual));

    $individual = $individual->fresh();

    $this->assertTrue($individual->checkStatus('published'));
});

test('individual pages can be unpublished', function () {
    $individual = Individual::factory()->create(['roles' => ['consultant']]);

    $response = $this->actingAs($individual->user)->from(localized_route('individuals.show', $individual))->put(localized_route('individuals.update-publication-status', $individual), [
        'unpublish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.show', $individual));

    $individual = $individual->fresh();

    $this->assertTrue($individual->checkStatus('draft'));
});

test('individual pages cannot be published by other users', function () {
    $user = User::factory()->create();
    $individual = Individual::factory()->create([
        'roles' => ['consultant'],
        'published_at' => null,
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-publication-status', $individual), [
        'publish' => true,
    ]);

    $response->assertForbidden();

    $individual = $individual->fresh();
    $this->assertTrue($individual->checkStatus('draft'));
});

test('individual isPublishable()', function ($expected, $data, $userData, $connections = []) {
    $this->seed(IdentitySeeder::class);

    $individualUser = User::factory()->create();
    $individualUser->update($userData);
    $individualUser = $individualUser->fresh();
    $individual = $individualUser->individual;
    $individual->update($data);
    $individual = $individual->fresh();

    $indigenousIdentity = Identity::whereJsonContains('clusters', IdentityCluster::Indigenous)->first();
    $ageBracket = Identity::whereJsonContains('clusters', IdentityCluster::Age)->first();

    foreach ($connections as $connection) {
        if ($connection === 'areaTypeConnections') {
            $individual->areaTypeConnections()->attach($this->areaType->id);
        }

        if ($connection === 'indigenousConnections') {
            $individual->indigenousConnections()->attach($indigenousIdentity->id);
        }

        if ($connection === 'ageBracketConnections') {
            $individual->ageBracketConnections()->attach($ageBracket->id);
        }
    }

    expect($individual->isPublishable())->toBe($expected);
})->with('individualIsPublishable');

test('draft individuals do not appear on individual index', function () {
    $user = User::factory()->create();
    $individual = Individual::factory()->create([
        'published_at' => null,
        'roles' => ['consultant'],
    ]);

    $response = $this->actingAs($user)->get(localized_route('individuals.index'));
    $response->assertDontSee($individual->name);
});

test('published individuals appear on individual index', function () {
    $user = User::factory()->create();
    $individual = Individual::factory()->create([
        'roles' => ['consultant'],
    ]);

    $response = $this->actingAs($user)->get(localized_route('individuals.index'));
    $response->assertSee($individual->name);
});

test('individuals can participate in engagements', function () {
    $participant = Individual::factory()->create(['roles' => ['participant']]);
    $engagement = Engagement::factory()->create();
    $engagement->participants()->attach($participant->id, ['status' => 'confirmed']);

    expect($participant->engagements)->toHaveCount(1);
});

test('individual view routes can be retrieved based on role', function () {
    $user = User::factory()->create();
    $individual = $user->individual;

    expect($individual->steps()[2]['show'])->toEqual('individuals.show-experiences');

    $individual->roles = ['connector'];
    $individual->save();

    $individual = $individual->fresh();

    expect($individual->steps()[2]['show'])->toEqual('individuals.show');
});

test('individual relationships to projects can be derived from both projects and engagements', function () {
    $individual = Individual::factory()->create(['roles' => ['participant', 'consultant', 'connector']]);

    $individual = $individual->fresh();

    $connectingEngagement = Engagement::factory()->create([
        'individual_connector_id' => $individual->id,
    ]);

    expect($connectingEngagement->connector->id)->toEqual($individual->id);

    $connectingEngagementProject = $connectingEngagement->project;

    $participatingEngagement = Engagement::factory()->create();

    $participatingEngagement->participants()->attach($individual->id, ['status' => 'confirmed']);

    $participatingEngagement = $participatingEngagement->fresh();

    $participatingEngagementProject = $participatingEngagement->project;

    expect($individual->contractedProjects->pluck('id')->toArray())
        ->toHaveCount(1)
        ->toContain($connectingEngagementProject->id);

    expect($individual->participatingProjects->pluck('id')->toArray())
        ->toHaveCount(1)
        ->toContain($participatingEngagementProject->id);
});

test('individual consulting methods can be displayed', function () {
    $individual = Individual::factory()->create(['consulting_methods' => ['survey']]);
    expect($individual->display_consulting_methods)->toContain(EngagementFormat::labels()['survey']);
});

test('identities can be attached to an individual', function () {
    $user = User::factory()->create();
    $individual = $user->individual;

    $disabilityOrDeafIdentity = Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first();
    $individual->identities()->sync([$disabilityOrDeafIdentity->id]);
    $individual->refresh();

    expect($individual->identities->pluck('id')->toArray())->toContain($disabilityOrDeafIdentity->id);
    expect($individual->identities->count())->toEqual(1);
});

test('individuals with signed language can update about info', function () {
    $individual = Individual::factory()
        ->hasUser([
            'locale' => 'asl',
        ])
        ->create([
            'languages' => ['asl'],
            'roles' => ['connector'],
        ]);

    $user = $individual->user;

    $response = $this->actingAs($user)->get(localized_route('individuals.edit', $individual));
    $response->assertOk();
    $response->assertSee('name="pronouns[en]"', false);
    $response->assertSee('name="bio[en]"', false);
    $response->assertDontSee('name="pronouns[asl]"', false);
    $response->assertDontSee('name="bio[asl]"', false);

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => 'NS',
        'pronouns' => ['en' => 'they/them'],
        'bio' => ['en' => 'This is my bio.'],
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
    $individual = $individual->refresh();

    expect($individual->getTranslation('pronouns', 'en'))->toEqual('they/them');
    expect($individual->getTranslation('bio', 'en'))->toEqual('This is my bio.');
});

test('individuals with signed language can update about experiences', function () {
    $individual = Individual::factory()
        ->hasUser([
            'locale' => 'asl',
        ])
        ->create([
            'languages' => ['asl'],
            'roles' => ['connector'],
        ]);

    $user = $individual->user;

    $response = $this->actingAs($user)->get(localized_route('individuals.edit', [
        'individual' => $individual,
        'step' => 3,
    ]));

    $response->assertOk();
    $response->assertSee('name="lived_experience[en]"', false);
    $response->assertSee('name="skills_and_strengths[en]"', false);
    $response->assertDontSee('name="lived_experience[asl]"', false);
    $response->assertDontSee('name="skills_and_strengths[asl]"', false);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-experiences', $individual), [
        'lived_experience' => ['en' => 'My lived experiences.'],
        'skills_and_strengths' => ['en' => 'My skills and strengths.'],
        'save' => __('Save'),
    ]);

    $response->assertSessionHasNoErrors();
    $individual = $individual->refresh();

    expect($individual->getTranslation('lived_experience', 'en'))->toEqual('My lived experiences.');
    expect($individual->getTranslation('skills_and_strengths', 'en'))->toEqual('My skills and strengths.');
});
