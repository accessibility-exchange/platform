<?php

use App\Http\Requests\UpdateIndividualConstituenciesRequest;
use App\Models\AgeBracket;
use App\Models\AreaType;
use App\Models\Constituency;
use App\Models\DisabilityType;
use App\Models\Engagement;
use App\Models\EthnoracialIdentity;
use App\Models\GenderIdentity;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\IndividualRole;
use App\Models\LivedExperience;
use App\Models\Sector;
use App\Models\User;
use Database\Seeders\AgeBracketSeeder;
use Database\Seeders\AreaTypeSeeder;
use Database\Seeders\ConstituencySeeder;
use Database\Seeders\DisabilityTypeSeeder;
use Database\Seeders\EthnoracialIdentitySeeder;
use Database\Seeders\GenderIdentitySeeder;
use Database\Seeders\IndividualRoleSeeder;
use Database\Seeders\LivedExperienceSeeder;

test('individual users can select an individual role', function () {
    $this->seed(IndividualRoleSeeder::class);
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('individuals.show-role-selection'));
    $response->assertOk();

    $firstRole = IndividualRole::first();

    $response = $this->actingAs($user)
        ->from(localized_route('individuals.show-role-selection'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => [$firstRole->id],
        ]);

    $response->assertRedirect(localized_route('dashboard'));

    $user = $user->fresh();
    expect($user->individual->individualRoles[0]->id)->toEqual($firstRole->id);
});

test('non-individuals cannot select an individual role', function () {
    $nonCommunityUser = User::factory()->create([
        'context' => 'regulated-organization',
    ]);

    $response = $this->actingAs($nonCommunityUser)->get(localized_route('individuals.show-role-selection'));
    $response->assertForbidden();
});

test('individuals can edit their roles', function () {
    $this->seed(IndividualRoleSeeder::class);
    $user = User::factory()->create();

    $individual = $user->individual;

    $participantRole = IndividualRole::where('name->en', 'Consultation Participant')->first();
    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();
    $individual->individualRoles()->sync([$consultantRole->id]);
    $individual->publish();

    $individual = $individual->fresh();

    $response = $this->actingAs($user)
        ->get(localized_route('individuals.show-role-edit'));

    $response->assertSee('<input x-model.number="roles" type="checkbox" name="roles[]" id="roles-'.$participantRole->id.'" value="'.$participantRole->id.'" aria-describedby="roles-'.$participantRole->id.'-hint"   />', false);
    $response->assertSee('<input x-model.number="roles" type="checkbox" name="roles[]" id="roles-'.$consultantRole->id.'" value="'.$consultantRole->id.'" aria-describedby="roles-'.$consultantRole->id.'-hint" checked  />', false);

    $response = $this->actingAs($user)
        ->from(localized_route('individuals.show-role-edit'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => [$participantRole->id],
        ]);

    $individual = $individual->fresh();

    expect($individual->checkStatus('published'))->toBeFalse();
});

test('users can create individual pages', function () {
    $this->seed();

    $response = $this->withSession([
        'locale' => 'en',
        'signed_language' => 'ase',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => 'individual',
    ])->post(localized_route('register-store'), [
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();

    $user = Auth::user();
    $individual = $user->individual;

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    expect($individual)->toBeInstanceOf(Individual::class);

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'locality' => 'Halifax',
        'region' => 'NS',
        'pronouns' => [],
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            'planning-consultation',
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
            'planning-consultation',
            'running-consultation',
        ],
        'publish' => __('Publish'),
    ]);

    $response->assertSessionHasNoErrors();
    $individual = $individual->fresh();
    $this->assertTrue($individual->checkStatus('published'));

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => 'NS',
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            'planning-consultation',
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
            'planning-consultation',
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
                'planning-consultation',
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
        'preferred_contact_method' => 'email',
        'preferred_contact_person' => 'me',
        'meeting_types' => ['in_person', 'web_conference'],
        'save' => __('Save'),
    ]);

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
    expect($individual->user->support_person_phone)->toEqual('+14384444567');

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));
});

test('entity users can not create individual pages', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    expect($user->individual)->toBeNull();
});

test('individuals with connector role can represent individuals with disabilities', function () {
    $this->seed(IndividualRoleSeeder::class);
    $this->seed(LivedExperienceSeeder::class);
    $this->seed(GenderIdentitySeeder::class);
    $this->seed(AreaTypeSeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $connectorRole = IndividualRole::where('name->en', 'Community Connector')->first();
    $livedExperience = LivedExperience::first();
    $areaType = AreaType::first();

    $individual->individualRoles()->sync([$connectorRole->id]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), []);

    $response->assertSessionHasErrors();

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experiences' => [$livedExperience->id],
        'area_types' => [$areaType->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->livedExperienceConnections)->toHaveCount(1);
    expect($individual->base_disability_type)->toEqual('specific_disabilities');
    expect($individual->has_nb_gnc_fluid_constituents)->toBeFalse();
    expect($livedExperience->communityConnectors)->toHaveCount(1);
});

test('individuals with connector role can represent cross-disability individuals', function () {
    $this->seed(IndividualRoleSeeder::class);
    $this->seed(LivedExperienceSeeder::class);
    $this->seed(DisabilityTypeSeeder::class);
    $this->seed(GenderIdentitySeeder::class);
    $this->seed(AreaTypeSeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $connectorRole = IndividualRole::where('name->en', 'Community Connector')->first();
    $livedExperience = LivedExperience::first();
    $crossDisability = DisabilityType::where('name->en', 'Cross-disability')->first();
    $areaType = AreaType::first();

    $individual->individualRoles()->sync([$connectorRole->id]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experiences' => [$livedExperience->id],
        'base_disability_type' => 'cross_disability',
        'area_types' => [$areaType->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->base_disability_type)->toEqual('cross_disability');
    expect($crossDisability->communityConnectors)->toHaveCount(1);
});

test('individuals with connector role can represent individuals in specific age brackets', function () {
    $this->seed(IndividualRoleSeeder::class);
    $this->seed(LivedExperienceSeeder::class);
    $this->seed(GenderIdentitySeeder::class);
    $this->seed(AreaTypeSeeder::class);
    $this->seed(AgeBracketSeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $connectorRole = IndividualRole::where('name->en', 'Community Connector')->first();
    $livedExperience = LivedExperience::first();
    $areaType = AreaType::first();
    $ageBracket = AgeBracket::first();

    $individual->individualRoles()->sync([$connectorRole->id]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experiences' => [$livedExperience->id],
        'area_types' => [$areaType->id],
        'has_age_brackets' => 1,
        'age_brackets' => [$ageBracket->id],
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->ageBracketConnections)->toHaveCount(1);
    expect($individual->extra_attributes->has_age_brackets)->toBeTruthy();
    expect($ageBracket->communityConnectors)->toHaveCount(1);
});

test('individuals with connector role can represent refugees and immigrants', function () {
    $this->seed(IndividualRoleSeeder::class);
    $this->seed(LivedExperienceSeeder::class);
    $this->seed(GenderIdentitySeeder::class);
    $this->seed(AreaTypeSeeder::class);
    $this->seed(ConstituencySeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $connectorRole = IndividualRole::where('name->en', 'Community Connector')->first();
    $livedExperience = LivedExperience::first();
    $areaType = AreaType::first();
    $refugeesAndImmigrants = Constituency::where('name->en', 'Refugee or immigrant')->first();

    $individual->individualRoles()->sync([$connectorRole->id]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experiences' => [$livedExperience->id],
        'area_types' => [$areaType->id],
        'refugees_and_immigrants' => 1,
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->constituencyConnections)->toHaveCount(1);
    expect($refugeesAndImmigrants->communityConnectors)->toHaveCount(1);
});

test('individuals with connector role can represent gender and sexual minorities', function () {
    $this->seed(IndividualRoleSeeder::class);
    $this->seed(LivedExperienceSeeder::class);
    $this->seed(GenderIdentitySeeder::class);
    $this->seed(AreaTypeSeeder::class);
    $this->seed(ConstituencySeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $connectorRole = IndividualRole::where('name->en', 'Community Connector')->first();
    $livedExperience = LivedExperience::first();
    $areaType = AreaType::first();
    $women = GenderIdentity::where('name_plural->en', 'Women')->first();
    $nb = GenderIdentity::where('name_plural->en', 'Non-binary people')->first();
    $gnc = GenderIdentity::where('name_plural->en', 'Gender non-conforming people')->first();
    $fluid = GenderIdentity::where('name_plural->en', 'Gender fluid people')->first();
    $transPeople = Constituency::where('name_plural->en', 'Trans people')->first();
    $twoslgbtqiaplusPeople = Constituency::where('name_plural->en', '2SLGBTQIA+ people')->firstOrFail();

    $individual->individualRoles()->sync([$connectorRole->id]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experiences' => [$livedExperience->id],
        'area_types' => [$areaType->id],
        'has_gender_and_sexual_identities' => 1,
        'gender_and_sexual_identities' => [
            'women',
            'nb-gnc-fluid-people',
            'trans-people',
            '2slgbtqiaplus-people',
        ],
    ]);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->constituencyConnections)->toHaveCount(2);
    expect($individual->genderIdentityConnections)->toHaveCount(4);
    expect($individual->has_nb_gnc_fluid_constituents)->toBeTrue();
    expect($individual->extra_attributes->has_gender_and_sexual_identities)->toBeTruthy();
    expect($nb->communityConnectors)->toHaveCount(1);
    expect($gnc->communityConnectors)->toHaveCount(1);
    expect($fluid->communityConnectors)->toHaveCount(1);
    expect($transPeople->communityConnectors)->toHaveCount(1);
    expect($twoslgbtqiaplusPeople->communityConnectors)->toHaveCount(1);
});

test('individuals with connector role can represent ethnoracial identities', function () {
    $this->seed(IndividualRoleSeeder::class);
    $this->seed(LivedExperienceSeeder::class);
    $this->seed(GenderIdentitySeeder::class);
    $this->seed(EthnoracialIdentitySeeder::class);
    $this->seed(AreaTypeSeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $connectorRole = IndividualRole::where('name->en', 'Community Connector')->first();
    $livedExperience = LivedExperience::first();
    $ethnoracialIdentity = EthnoracialIdentity::where('name->en', 'Black')->first();
    $areaType = AreaType::first();

    $individual->individualRoles()->sync([$connectorRole->id]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experiences' => [$livedExperience->id],
        'ethnoracial_identities' => [$ethnoracialIdentity->id],
        'area_types' => [$areaType->id],
    ]);

    unset($data['other_ethnoracial']);

    $response = $this->actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $response->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->ethnoracialIdentityConnections)->toHaveCount(1);
    expect($individual->other_ethnoracial_identity_connections)->toBeNull();
    expect($ethnoracialIdentity->communityConnectors)->toHaveCount(1);
});

test('individuals can have participant role', function () {
    $this->seed(IndividualRoleSeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $participantRole = IndividualRole::where('name->en', 'Consultation Participant')->first();
    $individual->individualRoles()->sync([$participantRole->id]);

    expect($individual->isParticipant())->toBeTrue();
});

test('individuals can have consultant role', function () {
    $this->seed(IndividualRoleSeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();
    $individual->individualRoles()->sync([$consultantRole->id]);

    expect($individual->isConsultant())->toBeTrue();
});

test('users can edit individual pages', function () {
    $this->seed(IndividualRoleSeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    expect($individual->isPublishable())->toBeFalse();

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($user)->get(localized_route('individuals.edit', $individual));
    $response->assertOk();

    $response = $this->actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $individual->name,
        'bio' => ['en' => $individual->bio],
        'consulting_services' => [
            'planning-consultation',
            'running-consultation',
        ],
        'locality' => 'St John\'s',
        'region' => 'NL',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 1]));

    $draftUser = User::factory()->create();
    $draftIndividual = $draftUser->individual;

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $draftIndividual->individualRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($draftUser)->get(localized_route('individuals.edit', $draftIndividual));
    $response->assertOk();

    $response = $this->actingAs($draftUser)->put(localized_route('individuals.update', $draftIndividual), [
        'name' => $draftIndividual->name,
        'bio' => ['en' => $draftIndividual->bio],
        'consulting_services' => [
            'planning-consultation',
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
    $this->seed(IndividualRoleSeeder::class);

    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $individual = $user->individual;

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

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
    $this->seed(IndividualRoleSeeder::class);

    $individual = Individual::factory()->create(['published_at' => null, 'consulting_services' => ['analysis']]);

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($individual->user)->get(localized_route('individuals.show', $individual));
    $response->assertOk();
});

test('users can not view others draft individual pages', function () {
    $otherUser = User::factory()->create();
    $individual = Individual::factory()->create(['published_at' => null]);

    $response = $this->actingAs($otherUser)->get(localized_route('individuals.show', $individual));
    $response->assertForbidden();
});

test('users can view individual pages', function () {
    $this->seed(IndividualRoleSeeder::class);

    $individual = Individual::factory()->create(['consulting_services' => ['analysis']]);

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    $individual->publish();
    $individual = $individual->fresh();

    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)->get(localized_route('individuals.show', $individual));
    $response->assertOk();
});

test('guests can not view individual pages', function () {
    $this->seed(IndividualRoleSeeder::class);

    $individual = Individual::factory()->create();

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    $response = $this->get(localized_route('individuals.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('individuals.show', $individual));
    $response->assertRedirect(localized_route('login'));
});

test('individual pages can be published', function () {
    $this->seed(IndividualRoleSeeder::class);

    $individual = Individual::factory()->create();

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($individual->user)->from(localized_route('individuals.show', $individual))->put(localized_route('individuals.update-publication-status', $individual), [
        'publish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.show', $individual));

    $individual = $individual->fresh();

    $this->assertTrue($individual->checkStatus('published'));
});

test('individual pages can be unpublished', function () {
    $this->seed(IndividualRoleSeeder::class);

    $individual = Individual::factory()->create();

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($individual->user)->from(localized_route('individuals.show', $individual))->put(localized_route('individuals.update-publication-status', $individual), [
        'unpublish' => true,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('individuals.show', $individual));

    $individual = $individual->fresh();

    $this->assertTrue($individual->checkStatus('draft'));
});

test('draft individuals do not appear on individual index', function () {
    $this->seed(IndividualRoleSeeder::class);

    $user = User::factory()->create();
    $individual = Individual::factory()->create([
        'published_at' => null,
    ]);

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($user)->get(localized_route('individuals.index'));
    $response->assertDontSee($individual->name);
});

test('published individuals appear on individual index', function () {
    $this->seed(IndividualRoleSeeder::class);

    $user = User::factory()->create();
    $individual = Individual::factory()->create();

    $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

    $individual->individualRoles()->sync([$consultantRole->id]);

    $response = $this->actingAs($user)->get(localized_route('individuals.index'));
    $response->assertSee($individual->name);
});

test('individuals can participate in engagements', function () {
    $participant = Individual::factory()->create();
    $engagement = Engagement::factory()->create();
    $engagement->participants()->attach($participant->id, ['status' => 'confirmed']);

    expect($participant->engagements)->toHaveCount(1);
});

test('individual view routes can be retrieved based on role', function () {
    $this->seed(IndividualRoleSeeder::class);

    $user = User::factory()->create();
    $individual = $user->individual;

    $connectorRole = IndividualRole::where('name->en', 'Community Connector')->first();

    expect($individual->steps()[2]['show'])->toEqual('individuals.show-experiences');

    $individual->individualRoles()->sync([$connectorRole->id]);

    $individual = $individual->fresh();

    expect($individual->steps()[2]['show'])->toEqual('individuals.show-constituencies');
});
