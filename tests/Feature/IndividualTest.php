<?php

use App\Enums\BaseDisabilityType;
use App\Enums\CommunityConnectorHasLivedExperience;
use App\Enums\ConsultingService;
use App\Enums\ContactMethod;
use App\Enums\ContactPerson;
use App\Enums\EngagementFormat;
use App\Enums\IdentityCluster;
use App\Enums\IndividualRole;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Http\Requests\UpdateIndividualCommunicationAndConsultationPreferencesRequest;
use App\Http\Requests\UpdateIndividualConstituenciesRequest;
use App\Http\Requests\UpdateIndividualRequest;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\Scopes\ReachableIdentityScope;
use App\Models\Sector;
use App\Models\User;
use App\Notifications\IndividualPublicPageNeedsUpdate;
use Database\Seeders\AccessSupportSeeder;
use Database\Seeders\IdentitySeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Laravel\withSession;

test('individual onboarding process', function () {
    seed(AccessSupportSeeder::class);

    $user = User::factory()
        ->hasIndividual(['viewed_payment_disclaimer' => null])
        ->create();

    // Without confirming payment disclaimer users are redirected back into the onboarding flow
    actingAs($user)
        ->get(localized_route('dashboard'))
        ->assertRedirect(localized_route('settings.edit-communication-and-consultation-preferences'))
        ->assertSessionHas('onboarding', true);

    // Communication and consultation preferences
    actingAs($user)
        ->withSession(['onboarding' => true])
        ->get(localized_route('settings.edit-communication-and-consultation-preferences'))
        ->assertOk()
        ->assertDontSeeText(__('Please indicate the types of consultations you are willing to do.'));

    actingAs($user)
        ->withSession(['onboarding' => true])
        ->put(localized_route('settings.edit-communication-and-consultation-preferences'), [
            'preferred_contact_person' => ContactPerson::Me->value,
            'email' => $user->email,
            'preferred_contact_method' => 'email',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-access-needs'))
        ->assertSessionHas('onboarding', true);

    // Access needs
    actingAs($user)
        ->withSession(['onboarding' => true])
        ->get(localized_route('settings.edit-access-needs'))
        ->assertOk();

    actingAs($user)
        ->withSession(['onboarding' => true])
        ->put(localized_route('settings.update-access-needs'), [])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.show-payment-disclaimer'))
        ->assertSessionHas('onboarding', true);

    // Payment disclaimer
    actingAs($user)
        ->withSession(['onboarding' => true])
        ->get(localized_route('individuals.show-payment-disclaimer'))
        ->assertOk();

    actingAs($user)
        ->withSession(['onboarding' => true])
        ->put(localized_route('individuals.update-payment-disclaimer-status'), ['viewed_payment_disclaimer' => true])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'))
        ->assertSessionMissing('onboarding');

    // Can now access dashboard
    actingAs($user)
        ->get(localized_route('dashboard'))
        ->assertOk();
});

test('update payment disclaimer status request validation errors', function () {
    $individual = Individual::factory()
        ->forUser()
        ->create(['viewed_payment_disclaimer' => null]);

    actingAs($individual->user)
        ->put(localized_route('individuals.update-payment-disclaimer-status'), ['viewed_payment_disclaimer' => ['not boolean']])
        ->assertSessionHasErrors(['viewed_payment_disclaimer' => __('validation.boolean', ['attribute' => __('viewed payment disclaimer')])]);
});

test('individual users can select an individual role', function () {
    $user = User::factory()->hasIndividual()->create();

    actingAs($user)->get(localized_route('individuals.show-role-selection'))
        ->assertOk()
        ->assertViewHas('defaultRoles', [IndividualRole::ConsultationParticipant->value]);

    actingAs($user)
        ->followingRedirects()
        ->from(localized_route('individuals.show-role-selection'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => [
                IndividualRole::ConsultationParticipant->value,
                IndividualRole::AccessibilityConsultant->value,
            ],
        ])
        ->assertSee('Your roles have been saved.');

    $user = $user->fresh();
    expect($user->individual->isParticipant())->toBeTrue();
});

test('non-individuals cannot select an individual role', function () {
    $nonCommunityUser = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
    ]);

    actingAs($nonCommunityUser)->get(localized_route('individuals.show-role-selection'))->assertForbidden();
});

test('individuals can edit their roles', function () {
    $user = User::factory()
        ->hasIndividual(['roles' => [
            IndividualRole::AccessibilityConsultant->value,
            IndividualRole::CommunityConnector->value,
        ]])
        ->create();
    $individual = $user->individual;

    actingAs($user)->get(localized_route('individuals.show-role-edit'))
        ->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-participant" value="'.IndividualRole::ConsultationParticipant->value.'" aria-describedby="roles-participant-hint"   />', false)
        ->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-consultant" value="'.IndividualRole::AccessibilityConsultant->value.'" aria-describedby="roles-consultant-hint" checked  />', false);

    actingAs($user)
        ->followingRedirects()
        ->from(localized_route('individuals.show-role-edit'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => [IndividualRole::ConsultationParticipant->value],
        ])
        ->assertSee('Your roles have been saved.');

    $individual = $individual->fresh();

    expect($individual->isPreviewable())->toBeFalse();
    expect($individual->isPublishable())->toBeFalse();
    expect($individual->checkStatus('published'))->toBeFalse();

    actingAs($user)
        ->followingRedirects()
        ->from(localized_route('individuals.show-role-edit'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => [IndividualRole::AccessibilityConsultant->value],
        ])
        ->assertSee('Your roles have been saved. Please review your page.');

    $individual = $individual->fresh();

    actingAs($user)
        ->followingRedirects()
        ->from(localized_route('individuals.show-role-edit'))
        ->put(localized_route('individuals.save-roles'), [
            'roles' => [
                IndividualRole::AccessibilityConsultant->value,
                IndividualRole::ConsultationParticipant->value,
            ],
        ])
        ->assertDontSee('Your roles have been saved. Please review your page.')
        ->assertSee('Your roles have been saved.');
});

test('flash message and notification after individual’s role changed', function ($initialRoles, $newRoles, $expected) {
    Notification::fake();

    $user = User::factory()
        ->hasIndividual(['roles' => $initialRoles])
        ->create();
    $individual = $user->individual;

    actingAs($user)
        ->put(localized_route('individuals.save-roles'), [
            'roles' => $newRoles,
        ])
        ->assertSessionHasNoErrors();

    expect(flash()->class)->toStartWith($expected['class']);
    expect(flash()->message)->toBe($expected['message']($individual));

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee($expected['message']($individual));

    if (! empty($expected['notification'])) {
        Notification::assertSentTo(
            $user,
            function (IndividualPublicPageNeedsUpdate $notification, $channels) use ($user, $individual) {
                expect($notification->toMail($user)->subject)->toBe(__('Please review your page'));
                $renderedMail = $notification->toMail($user)->render();

                $this->assertStringContainsString(__('Please review your page. There is some information for your new role that you will have to fill in.'), $renderedMail);
                $this->assertStringContainsString(localized_route('individuals.edit', $individual), $renderedMail);
                $this->assertStringContainsString(__('Edit my public page'), $renderedMail);

                $this->assertStringContainsString(__('Please review your page. There is some information for your new role that you will have to fill in.'), $notification->toVonage($user)->content);

                expect($notification->toArray($user)['individual_id'])->toEqual($individual->id);

                return $notification->individual->id === $individual->id;
            }
        );
    }
})->with('individualRoleChange');

test('users can access page needs update notification', function () {
    $user = User::factory()
        ->hasIndividual(['roles' => [IndividualRole::CommunityConnector->value]])
        ->create();
    $individual = $user->individual;

    $user->notify(new IndividualPublicPageNeedsUpdate($individual));

    actingAs($user)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSeeInOrder([
            __('Please review your page.'),
            __('There is some information for your new role that you will have to fill in.'),
            localized_route('individuals.edit', $individual),
            __('Edit my public page'),
        ]);
});

test('save roles request validation errors', function (array $data, array $errors) {
    $individual = Individual::factory()
        ->for(User::factory())
        ->create(['roles' => null]);

    actingAs($individual->user)
        ->put(localized_route('individuals.save-roles'), $data)
        ->assertSessionHasErrors($errors);
})->with('saveIndividualRolesRequestValidationErrors');

test('users can create individual pages', function () {
    seed(ImpactSeeder::class);
    seed(SectorSeeder::class);

    $livedExperience = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::LivedExperience->value],
    ]);
    $areaType = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::Area->value],
    ]);

    withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => UserContext::Individual->value,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ]);

    assertAuthenticated();

    $user = Auth::user();
    $user->update(['oriented_at' => now()]);

    $user = $user->fresh();
    $individual = $user->individual;

    $individual->fill([
        'roles' => [IndividualRole::AccessibilityConsultant->value],
        'connection_lived_experience' => CommunityConnectorHasLivedExperience::YesAll->value,
        'meeting_types' => [MeetingType::InPerson->value],
    ]);
    $individual->save();

    $individual->identityConnections()->attach($livedExperience->id);
    $individual->identityConnections()->attach($areaType->id);

    expect($individual)->toBeInstanceOf(Individual::class);

    $response = actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'locality' => 'Halifax',
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'pronouns' => [],
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'social_links' => [
            'linked_in' => 'https://linkedin.com/in/someone',
            'twitter' => '',
            'instagram' => '',
            'facebook' => '',
        ],
        'website_links' => 'https://example.com',
        'save' => __('Save'),
    ])
        ->assertSessionHasNoErrors();
    $individual = $individual->fresh();

    expect($individual->social_links)->toHaveKey('linked_in')->toHaveCount(1);

    $response->assertSessionHasNoErrors()->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 1]));

    actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'publish' => __('Publish'),
    ])
        ->assertSessionHasNoErrors();
    $individual = $individual->fresh();
    expect($individual->checkStatus('published'))->toBeTrue();

    actingAs($user)->followingRedirects()->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'save' => __('Save'),
    ])
        ->assertSee('You have successfully saved your individual page.');

    actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'unpublish' => __('Unpublish'),
    ])
        ->assertSessionHasNoErrors();
    $individual = $individual->fresh();
    expect($individual->checkStatus('published'))->toBeFalse();

    actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'bio' => ['en' => 'This is my bio.'],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'preview' => __('Preview'),
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.show', ['individual' => $individual]));

    actingAs($user)
        ->from(localized_route('individuals.edit', $individual))
        ->put(localized_route('individuals.update', $individual), [
            'name' => $user->name,
            'locality' => 'Halifax',
            'region' => ProvinceOrTerritory::NovaScotia->value,
            'pronouns' => '',
            'bio' => ['en' => 'This is my bio.'],
            'consulting_services' => [
                ConsultingService::DesigningConsultation->value,
                ConsultingService::RunningConsultation->value,
            ],
            'save_and_next' => __('Save and next'),
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 2]));

    actingAs($user)->put(localized_route('individuals.update-interests', $individual), [
        'sectors' => [Sector::pluck('id')->first()],
        'impacts' => [Impact::pluck('id')->first()],
        'save_and_previous' => __('Save and previous'),
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 2]));

    actingAs($user)->put(localized_route('individuals.update-experiences', $individual), [
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
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 3]));

    actingAs($user)->put(localized_route('individuals.update-experiences', $individual), [
        'lived_experience' => '',
        'skills_and_strengths' => '',
        'relevant_experiences' => [],
        'save_and_next' => __('Save and next'),
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 3]));

    $individual = $individual->fresh();

    expect($individual->relevant_experiences)->toHaveCount(0);

    actingAs($user)->put(localized_route('individuals.update-experiences', $individual), [
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
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 3]));

    $individual = $individual->fresh();

    expect($individual->relevant_experiences)->toHaveCount(1);

    $response = actingAs($user)->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), [
        'email' => 'me@here.com',
        'phone' => '902-444-4567',
        'vrs' => true,
        'preferred_contact_method' => ContactMethod::Email->value,
        'preferred_contact_person' => ContactPerson::Me->value,
        'meeting_types' => [
            MeetingType::InPerson->value,
            MeetingType::WebConference->value,
        ],
        'save' => __('Save'),
    ]);

    $individual->refresh();

    expect($individual->user->vrs)->toBeTrue();

    $response->assertSessionHasNoErrors()->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));

    $response = actingAs($user)->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), [
        'email' => 'me@here.com',
        'phone' => '902-444-4567',
        'preferred_contact_method' => ContactMethod::Email->value,
        'preferred_contact_person' => ContactPerson::Me->value,
        'meeting_types' => [
            MeetingType::InPerson->value,
            MeetingType::WebConference->value,
        ],
        'save' => __('Save'),
    ]);

    $individual->refresh();
    expect($individual->user->vrs)->toBeNull();

    $response->assertSessionHasNoErrors()->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));

    $response = actingAs($user)->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), [
        'email' => 'me@here.com',
        'phone' => '902-444-4567',
        'support_person_name' => 'Someone',
        'support_person_email' => 'me@here.com',
        'support_person_phone' => '438-444-4567',
        'support_person_vrs' => true,
        'preferred_contact_method' => ContactMethod::Email->value,
        'preferred_contact_person' => ContactPerson::SupportPerson->value,
        'meeting_types' => [
            MeetingType::InPerson->value,
            MeetingType::WebConference->value,
        ],
        'save' => __('Save'),
    ]);

    $individual = $individual->fresh();

    expect($individual->user->phone)->toEqual('');
    expect($individual->user->support_person_phone)->toEqual('+14384444567');
    expect($individual->user->support_person_vrs)->toBeTrue();

    $response->assertSessionHasNoErrors()->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));

    $response = actingAs($user)->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), [
        'email' => 'me@here.com',
        'phone' => '902-444-4567',
        'support_person_name' => 'Someone',
        'support_person_email' => 'me@here.com',
        'support_person_phone' => '438-444-4567',
        'preferred_contact_method' => ContactMethod::Email->value,
        'preferred_contact_person' => ContactPerson::SupportPerson->value,
        'meeting_types' => [
            MeetingType::InPerson->value,
            MeetingType::WebConference->value,
        ],
        'save' => __('Save'),
    ]);

    $individual = $individual->fresh();

    expect($individual->user->phone)->toEqual('');
    expect($individual->user->support_person_vrs)->toBeNull();

    $response->assertSessionHasNoErrors()->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 4]));
});

test('update individual experiences request validation errors', function ($state, array $errors) {
    $individual = Individual::factory()
        ->for(User::factory())
        ->create([
            'roles' => [
                IndividualRole::CommunityConnector->value,
                IndividualRole::ConsultationParticipant->value,
            ],
        ]);

    actingAs($individual->user)
        ->put(localized_route('individuals.update-experiences', $individual), $state)
        ->assertSessionHasErrors($errors);
})->with('updateIndividualExperiencesRequestValidationErrors');

test('update individual interests request validation errors', function (array $state, array $errors) {
    $individual = Individual::factory()
        ->for(User::factory())
        ->create([
            'roles' => [
                IndividualRole::CommunityConnector->value,
                IndividualRole::ConsultationParticipant->value,
            ],
        ]);

    actingAs($individual->user)
        ->put(localized_route('individuals.update-interests', $individual), $state)
        ->assertSessionHasErrors($errors);
})->with('updateIndividualInterestsRequestValidationErrors');

test('update individual communication and consultation preferences request validation errors', function (array $state, array $errors, array $without = []) {
    $individual = Individual::factory()
        ->for(User::factory())
        ->create([
            'roles' => [
                IndividualRole::CommunityConnector->value,
                IndividualRole::ConsultationParticipant->value,
            ],
        ]);

    $data = UpdateIndividualCommunicationAndConsultationPreferencesRequest::factory()->without($without ?? [])->create($state);

    actingAs($individual->user)
        ->put(localized_route('individuals.update-communication-and-consultation-preferences', $individual), $data)
        ->assertSessionHasErrors($errors);
})->with('updateIndividualCommunicationAndConsultationPreferencesRequestValidationErrors');

test('entity users can not create individual pages', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    expect($user->individual)->toBeNull();
});

test('individuals with connector role can represent individuals with disabilities', function () {
    $user = User::factory()
        ->hasIndividual(['roles' => [IndividualRole::CommunityConnector->value]])
        ->create();
    $individual = $user->individual;

    $livedExperience = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::LivedExperience->value],
    ]);
    $areaType = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::Area->value],
    ]);

    expect($individual->base_disability_type)->toEqual('');
    expect($individual->hasConnections('disabilityAndDeafConnections'))->toBeNull();

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), [])->assertSessionHasErrors();

    $disabilityOrDeafIdentity = Identity::factory()->create(['clusters' => [IdentityCluster::DisabilityAndDeaf->value]]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$livedExperience->id],
        'area_type_connections' => [$areaType->id],
        'disability_and_deaf_connections' => [$disabilityOrDeafIdentity->id],
    ]);

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data)->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->livedExperienceConnections)->toHaveCount(1);
    expect($individual->base_disability_type)->toEqual(BaseDisabilityType::SpecificDisabilities->value);
    expect($individual->hasConnections('genderDiverseConnections'))->toBeFalse();
    expect($individual->hasConnections('disabilityAndDeafConnections'))->toBeTrue();
    expect($individual->disabilityAndDeafConnections)->toHaveCount(1);
    expect($livedExperience->communityConnectors)->toHaveCount(1);
    expect($individual->other_disability_connection)->toEqual('Something not listed');

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$livedExperience->id],
        'disability_and_deaf' => false,
        'base_disability_type' => null,
        'area_type_connections' => [$areaType->id],
        'has_other_disability_connection' => null,
    ]);

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $individual->refresh();

    expect($individual->extra_attributes->get('disability_and_deaf_connections'))->toBeNull();
    expect($individual->other_disability_connection)->toBeEmpty();
});

test('individuals with connector role can represent cross-disability individuals', function () {
    $user = User::factory()
        ->hasIndividual(['roles' => [IndividualRole::CommunityConnector->value]])
        ->create();
    $individual = $user->individual;

    $livedExperience = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::LivedExperience->value],
    ]);
    $areaType = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::Area->value],
    ]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$livedExperience->id],
        'base_disability_type' => BaseDisabilityType::CrossDisability->value,
        'area_type_connections' => [$areaType->id],
    ]);

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data)->assertSessionHasNoErrors();

    $individual->refresh();

    expect($individual->base_disability_type)->toEqual(BaseDisabilityType::CrossDisability->value);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$livedExperience->id],
        'disability_and_deaf' => false,
        'base_disability_type' => null,
        'area_type_connections' => [$areaType->id],
    ]);

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data);

    $individual->refresh();

    expect($individual->extra_attributes->get('cross_disability_and_deaf_connections'))->toBeNull();
});

test('individuals with connector role can represent individuals in specific age brackets', function () {
    $user = User::factory()
        ->hasIndividual(['roles' => [IndividualRole::CommunityConnector->value]])
        ->create();
    $individual = $user->individual;

    $livedExperience = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::LivedExperience->value],
    ]);
    $areaType = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::Area->value],
    ]);

    $ageBracket = Identity::factory()->create(['clusters' => [IdentityCluster::Age->value]]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$livedExperience->id],
        'area_type_connections' => [$areaType->id],
        'has_age_bracket_connections' => 1,
        'age_bracket_connections' => [$ageBracket->id],
    ]);

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data)->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->ageBracketConnections)->toHaveCount(1);
    expect($ageBracket->communityConnectors)->toHaveCount(1);
});

test('individuals with connector role can represent refugees and immigrants', function () {
    seed(IdentitySeeder::class);

    $livedExperience = Identity::withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::LivedExperience)->first();
    $areaType = Identity::whereJsonContains('clusters', IdentityCluster::Area)->first();

    $user = User::factory()
        ->hasIndividual(['roles' => [IndividualRole::CommunityConnector->value]])
        ->create();
    $individual = $user->individual;

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$livedExperience->id],
        'area_type_connections' => [$areaType->id],
        'refugees_and_immigrants' => 1,
    ]);

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data)->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->statusConnections)->toHaveCount(2);
});

test('individuals with connector role can represent gender and sexual minorities', function () {
    seed(IdentitySeeder::class);

    $livedExperience = Identity::withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::LivedExperience)->first();
    $areaType = Identity::whereJsonContains('clusters', IdentityCluster::Area)->first();

    $user = User::factory()
        ->hasIndividual(['roles' => [IndividualRole::CommunityConnector->value]])
        ->create();
    $individual = $user->individual;

    $genderAndSexualIdentities = array_merge(Identity::whereJsonContains('clusters', IdentityCluster::Gender)->whereNot(function ($query) {
        $query->whereJsonContains('clusters', IdentityCluster::GenderDiverse);
    })->pluck('id')->toArray(),
        Identity::whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->whereNot(function ($query) {
            $query->whereJsonContains('clusters', IdentityCluster::Gender);
        })->pluck('id')->toArray());

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$livedExperience->id],
        'area_type_connections' => [$areaType->id],
        'has_gender_and_sexuality_connections' => 1,
        'nb_gnc_fluid_identity' => 1,
        'gender_and_sexuality_connections' => $genderAndSexualIdentities,
    ]);

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data)->assertSessionHasNoErrors();

    $individual->refresh();

    expect($individual->genderIdentityConnections)->toHaveCount(4);
    expect($individual->genderAndSexualityConnections)->toHaveCount(6);
    expect($individual->hasConnections('genderDiverseConnections'))->toBeTrue();
});

test('individuals with connector role can represent ethnoracial identities', function () {
    $user = User::factory()
        ->hasIndividual(['roles' => [IndividualRole::CommunityConnector->value]])
        ->create();
    $individual = $user->individual;

    $livedExperience = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::LivedExperience->value],
    ]);
    $areaType = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::Area->value],
    ]);
    $ethnoracialIdentity = Identity::factory()->create(['clusters' => [IdentityCluster::Ethnoracial->value]]);

    $data = UpdateIndividualConstituenciesRequest::factory()->create([
        'lived_experience_connections' => [$livedExperience->id],
        'ethnoracial_identity_connections' => [$ethnoracialIdentity->id],
        'area_type_connections' => [$areaType->id],
    ]);

    unset($data['has_other_ethnoracial_identity_connection']);

    actingAs($user)->put(localized_route('individuals.update-constituencies', $individual), $data)->assertSessionHasNoErrors();

    $individual = $individual->fresh();

    expect($individual->ethnoracialIdentityConnections)->toHaveCount(1);
    expect($individual->other_ethnoracial_identity_connections)->toBeNull();
});

test('update individual constituences request validation errors', function (array $state, array $errors, array $without = []) {
    $individual = Individual::factory()
        ->for(User::factory())
        ->create(['roles' => [
            IndividualRole::CommunityConnector->value,
            IndividualRole::ConsultationParticipant->value,
        ],
        ]);

    $data = UpdateIndividualConstituenciesRequest::factory()->without($without ?? [])->create($state);

    actingAs($individual->user)
        ->put(localized_route('individuals.update-constituencies', $individual), $data)
        ->assertSessionHasErrors($errors);
})->with('updateIndividualConstituenciesRequestValidationErrors');

test('individuals can have participant role', function () {
    $individual = Individual::factory()->create(['roles' => [IndividualRole::ConsultationParticipant->value]]);

    expect($individual->isParticipant())->toBeTrue();
});

test('individuals can have consultant role', function () {
    $individual = Individual::factory()->create(['roles' => [IndividualRole::AccessibilityConsultant->value]]);

    expect($individual->isConsultant())->toBeTrue();
});

test('individuals can have connector role', function () {
    $individual = Individual::factory()->create(['roles' => [IndividualRole::CommunityConnector->value]]);

    expect($individual->isConnector())->toBeTrue();
});

test('users can edit individual pages', function () {
    $user = User::factory()
        ->hasIndividual([
            'roles' => null,
            'published_at' => null,
        ])
        ->create();
    $individual = $user->individual;

    expect($individual->isPublishable())->toBeFalse();

    $individual->roles = [IndividualRole::ConsultationParticipant->value];
    $individual->save();

    actingAs($user)->get(localized_route('individuals.edit', $individual))->assertNotFound();

    $individual->roles = [IndividualRole::AccessibilityConsultant->value];
    $individual->save();

    actingAs($user)->get(localized_route('individuals.edit', $individual))->assertOk();

    actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $individual->name,
        'bio' => ['en' => 'test bio'],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'locality' => 'St John’s',
        'region' => ProvinceOrTerritory::NewfoundlandAndLabrador->value,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 1]));

    $draftUser = User::factory()
        ->hasIndividual(['roles' => [IndividualRole::AccessibilityConsultant->value]])
        ->create();
    $draftIndividual = $draftUser->individual;

    actingAs($draftUser)->get(localized_route('individuals.edit', $draftIndividual))->assertOk();

    $response = actingAs($draftUser)->put(localized_route('individuals.update', $draftIndividual), [
        'name' => $draftIndividual->name,
        'bio' => ['en' => 'draft bio'],
        'consulting_services' => [
            ConsultingService::DesigningConsultation->value,
            ConsultingService::RunningConsultation->value,
        ],
        'locality' => 'St John’s',
        'region' => ProvinceOrTerritory::NewfoundlandAndLabrador->value,
        'working_languages' => [''],
    ]);

    $draftIndividual = $draftIndividual->fresh();

    expect($draftIndividual->working_languages)->toBeEmpty();

    $response->assertSessionHasNoErrors()->assertRedirect(localized_route('individuals.edit', ['individual' => $draftIndividual, 'step' => 1]));
});

test('users can not edit others individual pages', function () {
    $otherUser = User::factory()->create();

    $individual = Individual::factory()->create(['roles' => IndividualRole::AccessibilityConsultant->value]);

    actingAs($otherUser)->get(localized_route('individuals.edit', $individual))->assertForbidden();

    actingAs($otherUser)->put(localized_route('individuals.update', $individual), [
        'name' => $individual->name,
        'bio' => $individual->bio,
        'locality' => 'St John’s',
        'region' => ProvinceOrTerritory::NewfoundlandAndLabrador->value,
    ])
        ->assertForbidden();
});

test('update individual request validation errors', function (array $state, array $errors, array $without = []) {
    $roles = [
        IndividualRole::CommunityConnector->value,
        IndividualRole::ConsultationParticipant->value,
    ];

    if (array_key_exists('consulting_services', $state)) {
        $roles[] = IndividualRole::AccessibilityConsultant->value;
    }

    $individual = Individual::factory()
        ->for(User::factory())
        ->create(['roles' => $roles]);

    $data = UpdateIndividualRequest::factory()->without($without ?? [])->create($state);

    actingAs($individual->user)
        ->put(localized_route('individuals.update', $individual), $data)
        ->assertSessionHasErrors($errors);
})->with('updateIndividualRequestValidationErrors');

test('updating social links without an array should ignore the change', function () {
    $individual = Individual::factory()
        ->for(User::factory())
        ->create([
            'roles' => [
                IndividualRole::CommunityConnector->value,
                IndividualRole::ConsultationParticipant->value,
            ],
            'social_links' => [
                'facebook' => 'https://facebook.com',
            ],
        ]);

    actingAs($individual->user)
        ->put(localized_route('individuals.update', $individual), [
            'name' => $individual->name,
            'region' => $individual->region,
            'bio' => ['en' => 'base bio'],
            'social_links' => 'https://google.ca',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.edit', ['individual' => $individual, 'step' => 1]));

    $individual->refresh();
    expect($individual->social_links)->toHaveCount(1);
    expect($individual->social_links['facebook'])->toBe('https://facebook.com');
});

test('users can delete individual pages', function () {
    $user = User::factory()->hasIndividual()->create();

    actingAs($user)->delete(localized_route('individuals.destroy', $user->individual), [
        'current_password' => 'password',
    ])
        ->assertRedirect(localized_route('dashboard'));
});

test('users can not delete individual pages with wrong password', function () {
    $user = User::factory()->hasIndividual()->create();

    actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('individuals.destroy', $user->individual), [
        'current_password' => 'wrong_password',
    ])
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('dashboard'));
});

test('users can not delete others individual pages', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $individual = Individual::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    actingAs($user)->from(localized_route('dashboard'))->delete(localized_route('individuals.destroy', $individual), [
        'current_password' => 'password',
    ])
        ->assertForbidden();
});

test('destroy individual request validation errors', function (array $state, array $errors) {
    $individual = Individual::factory()
        ->for(User::factory())
        ->create([
            'roles' => [
                IndividualRole::CommunityConnector->value,
                IndividualRole::ConsultationParticipant->value,
            ],
        ]);

    actingAs($individual->user)
        ->delete(localized_route('individuals.destroy', $individual), $state)
        ->assertSessionHasErrorsIn('destroyIndividual', $errors);
})->with('destroyIndividualRequestValidationErrors');

test('users can view their own draft individual pages', function () {
    $individual = Individual::factory()->create([
        'published_at' => null,
        'consulting_services' => [ConsultingService::Analysis->value],
        'roles' => [IndividualRole::AccessibilityConsultant->value],
        'extra_attributes' => [
            'has_age_brackets' => true,
            'has_ethnoracial_identities' => true,
            'has_gender_and_sexual_identities' => true,
            'has_indigenous_identities' => true,
        ],
        'meeting_types' => [MeetingType::InPerson->value],
        'bio' => ['en' => 'ok'],
    ]);

    actingAs($individual->user)->get(localized_route('individuals.show', $individual))->assertOk();
});

test('users can not view others draft individual pages', function () {
    $otherUser = User::factory()->create();

    $individual = Individual::factory()->create(['published_at' => null, 'roles' => [IndividualRole::AccessibilityConsultant->value]]);

    actingAs($otherUser)->get(localized_route('individuals.show', $individual))->assertNotFound();
});

test('users can not view individual pages if they are not oriented', function () {
    $pendingUser = User::factory()->hasIndividual()->create(['oriented_at' => null]);
    actingAs($pendingUser)->get(localized_route('individuals.index'))->assertForbidden();

    $pendingUser->update(['oriented_at' => now()]);
    actingAs($pendingUser)->get(localized_route('individuals.index'))->assertOk();
});

test('organization or regulated organization users can not view individual pages if they are not oriented', function () {
    $organizationUser = User::factory()->create(['context' => UserContext::Organization->value, 'oriented_at' => null]);
    $organization = Organization::factory()->hasAttached($organizationUser, ['role' => TeamRole::Administrator->value])->create(['oriented_at' => null]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('individuals.index'))
        ->assertForbidden();

    $organization->update(['oriented_at' => now()]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('individuals.index'))
        ->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value, 'oriented_at' => null]);
    $regulatedOrganization = RegulatedOrganization::factory()->hasAttached($regulatedOrganizationUser, ['role' => TeamRole::Administrator->value])->create(['oriented_at' => null]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('individuals.index'))
        ->assertForbidden();

    $regulatedOrganization->update(['oriented_at' => now()]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('individuals.index'))
        ->assertOk();
});

test('users can view individual pages', function () {
    $individual = Individual::factory()->create([
        'consulting_services' => [ConsultingService::Analysis->value],
        'roles' => [IndividualRole::AccessibilityConsultant->value],
    ]);

    $individual->publish();
    $individual = $individual->fresh();

    $otherUser = User::factory()->create();

    actingAs($otherUser)->get(localized_route('individuals.show', $individual))->assertOk();
});

test('users can not view individual pages if the individual is not a consultant or connector', function () {
    $individual = Individual::factory()->create([
        'roles' => [IndividualRole::ConsultationParticipant->value],
    ]);

    $otherUser = User::factory()->create();

    actingAs($otherUser)->get(localized_route('individuals.show', $individual))->assertNotFound();
});

test('users without a verified email can not view individual pages', function () {
    $individual = Individual::factory()->create([
        'consulting_services' => [ConsultingService::Analysis->value],
        'roles' => [IndividualRole::AccessibilityConsultant->value],
    ]);

    $individual->publish();
    $individual = $individual->fresh();

    $user = User::factory()->create(['email_verified_at' => null]);

    actingAs($user)->get(localized_route('individuals.index'))->assertRedirect(localized_route('verification.notice'));

    actingAs($user)->get(localized_route('individuals.show', $individual))->assertRedirect(localized_route('verification.notice'));
});

test('guests can not view individual pages', function () {
    $individual = Individual::factory()->create(['roles' => [IndividualRole::AccessibilityConsultant->value]]);

    get(localized_route('individuals.index'))
        ->assertRedirect(localized_route('login'));

    get(localized_route('individuals.show', $individual))
        ->assertRedirect(localized_route('login'));
});

test('individual pages can be published', function () {
    $individual = Individual::factory()->create([
        'roles' => [IndividualRole::AccessibilityConsultant->value],
        'bio' => ['en' => 'Test bio'],
        'consulting_services' => [ConsultingService::BookingServiceProviders->value],
        'meeting_types' => [MeetingType::WebConference->value],
    ]);

    actingAs($individual->user)->from(localized_route('individuals.show', $individual))->put(localized_route('individuals.update-publication-status', $individual), [
        'publish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.show', $individual));

    $individual = $individual->fresh();

    expect($individual->checkStatus('published'))->toBeTrue();
});

test('individual pages can be unpublished', function () {
    $individual = Individual::factory()->create([
        'roles' => [IndividualRole::AccessibilityConsultant->value],
        'bio' => ['en' => 'Test bio'],
        'consulting_services' => [ConsultingService::BookingServiceProviders->value],
        'meeting_types' => [MeetingType::WebConference->value],
    ]);

    actingAs($individual->user)->from(localized_route('individuals.show', $individual))->put(localized_route('individuals.update-publication-status', $individual), [
        'unpublish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('individuals.show', $individual));

    $individual = $individual->fresh();

    expect($individual->checkStatus('draft'))->toBeTrue();
});

test('individual pages redirect to dashboard when unpublished and not previewable', function () {
    $individual = Individual::factory()->create([
        'roles' => [
            IndividualRole::AccessibilityConsultant->value,
            IndividualRole::CommunityConnector->value,
        ],
        'bio' => ['en' => 'Test bio'],
        'consulting_services' => [ConsultingService::BookingServiceProviders->value],
        'meeting_types' => [MeetingType::WebConference->value],
    ]);

    actingAs($individual->user)->from(localized_route('individuals.show', $individual))->put(localized_route('individuals.update-publication-status', $individual), [
        'unpublish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    $individual = $individual->fresh();

    expect($individual->checkStatus('draft'))->toBeTrue();
});

test('individual pages cannot be published by other users', function () {
    $user = User::factory()->create();
    $individual = Individual::factory()->create([
        'roles' => [IndividualRole::AccessibilityConsultant->value],
        'published_at' => null,
    ]);

    actingAs($user)->put(localized_route('individuals.update-publication-status', $individual), [
        'publish' => true,
    ])
        ->assertForbidden();

    $individual = $individual->fresh();
    expect($individual->checkStatus('draft'))->toBeTrue();
});

test('individual isPublishable()', function ($expected, $data, $userData, $connections = []) {
    $individual = Individual::factory()
        ->forUser($userData)
        ->create($data);

    $areaType = Identity::factory()->create([
        'description' => null,
        'clusters' => [IdentityCluster::Area->value],
    ]);
    $indigenousIdentity = Identity::factory()->create(['clusters' => [IdentityCluster::Indigenous->value]]);
    $ageBracket = Identity::factory()->create(['clusters' => [IdentityCluster::Age->value]]);

    foreach ($connections as $connection) {
        if ($connection === 'areaTypeConnections') {
            $individual->areaTypeConnections()->attach($areaType->id);
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
        'roles' => [IndividualRole::AccessibilityConsultant->value],
    ]);

    actingAs($user)->get(localized_route('individuals.index'))->assertDontSee($individual->name);
});

test('published individuals appear on individual index', function () {
    $user = User::factory()->create();
    $individual = Individual::factory()->create([
        'roles' => [IndividualRole::AccessibilityConsultant->value],
    ]);

    actingAs($user)->get(localized_route('individuals.index'))->assertSee($individual->name);
});

test('individuals can participate in engagements', function () {
    $participant = Individual::factory()->create(['roles' => [IndividualRole::ConsultationParticipant->value]]);
    $engagement = Engagement::factory()->create();
    $engagement->participants()->attach($participant->id, ['status' => 'confirmed']);

    expect($participant->engagements)->toHaveCount(1);
});

test('individual view routes can be retrieved based on role', function () {
    $individual = Individual::factory()->create();

    expect($individual->steps()[2]['show'])->toEqual('individuals.show-experiences');

    $individual->roles = [IndividualRole::CommunityConnector->value];
    $individual->save();

    $individual = $individual->fresh();

    expect($individual->steps()[2]['show'])->toEqual('individuals.show');
});

test('individual relationships to projects can be derived from both projects and engagements', function () {
    $individual = Individual::factory()->create(['roles' => [
        IndividualRole::ConsultationParticipant->value,
        IndividualRole::AccessibilityConsultant->value,
        IndividualRole::CommunityConnector->value,
    ]]);

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
    $individual = Individual::factory()->create(['consulting_methods' => [EngagementFormat::Survey->value]]);
    expect($individual->display_consulting_methods)->toContain(EngagementFormat::labels()[EngagementFormat::Survey->value]);
});

test('identities can be attached to an individual', function () {
    $individual = Individual::factory()->create();

    $disabilityOrDeafIdentity = Identity::factory()->create(['clusters' => [IdentityCluster::DisabilityAndDeaf->value]]);
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
            'roles' => [IndividualRole::CommunityConnector->value],
        ]);

    $user = $individual->user;

    actingAs($user)->get(localized_route('individuals.edit', $individual))
        ->assertOk()
        ->assertSee('name="pronouns[en]"', false)
        ->assertSee('name="bio[en]"', false)
        ->assertDontSee('name="pronouns[asl]"', false)
        ->assertDontSee('name="bio[asl]"', false);

    actingAs($user)->put(localized_route('individuals.update', $individual), [
        'name' => $user->name,
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'pronouns' => ['en' => 'they/them'],
        'bio' => ['en' => 'This is my bio.'],
        'save' => __('Save'),
    ])
        ->assertSessionHasNoErrors();
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
            'roles' => [IndividualRole::CommunityConnector->value],
        ]);

    $user = $individual->user;

    actingAs($user)->get(localized_route('individuals.edit', [
        'individual' => $individual,
        'step' => 3,
    ]))
        ->assertOk()
        ->assertSee('name="lived_experience[en]"', false)
        ->assertSee('name="skills_and_strengths[en]"', false)
        ->assertDontSee('name="lived_experience[asl]"', false)
        ->assertDontSee('name="skills_and_strengths[asl]"', false);

    actingAs($user)->put(localized_route('individuals.update-experiences', $individual), [
        'lived_experience' => ['en' => 'My lived experiences.'],
        'skills_and_strengths' => ['en' => 'My skills and strengths.'],
        'save' => __('Save'),
    ])
        ->assertSessionHasNoErrors();
    $individual = $individual->refresh();

    expect($individual->getTranslation('lived_experience', 'en'))->toEqual('My lived experiences.');
    expect($individual->getTranslation('skills_and_strengths', 'en'))->toEqual('My skills and strengths.');
});

test('Individual isInProgress()', function ($data, $withIdentity, $expected) {
    $individual = Individual::factory()
        ->create($data);

    if ($withIdentity) {
        $individual->identityConnections()->attach(Identity::factory()->create(['clusters' => [IdentityCluster::Age->value]]));
    }

    expect($individual->isInProgress())->toEqual($expected);

})->with('individualIsInProgress');

test('Individual isReady()', function ($userData, $indData, $expected) {
    $individual = Individual::factory()
        ->forUser($userData)
        ->create($indData);

    expect($individual->isReady())->toEqual($expected);

})->with('individualIsReady');

test('Individual getting started', function () {
    $user = User::factory()
        ->hasIndividual([
            'roles' => null,
            'published_at' => null,
        ])
        ->create(['oriented_at' => null]);
    $individual = $user->individual;

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder([
            __('Getting started'),
            __('Current step'),
            __('Sign up and attend an orientation session'),
            __('Next steps'),
            __('Pick your role'),
        ], false)
        ->assertDontSee(__('Fill in your collaboration preferences'), false)
        ->assertDontSee(__('Fill out and return your application'), false)
        ->assertDontSee(__('Create a public page'), false)
        ->assertDontSee(__('Completed steps'), false);

    $user->update(['oriented_at' => now()]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder([
            __('Getting started'),
            __('Current step'),
            __('Pick your role'),
            __('Next steps'),
            __('This will show up once you pick your role.'),
            __('Completed steps'),
            __('Sign up and attend an orientation session'),
        ], false)
        ->assertDontSee(__('Fill in your collaboration preferences'), false)
        ->assertDontSee(__('Fill out and return your application'), false)
        ->assertDontSee(__('Create a public page'), false)
        ->assertDontSee(__('Edit roles'), false);

    $individual->update(['roles' => [IndividualRole::ConsultationParticipant->value]]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder([
            __('Getting started'),
            __('Browse engagements'),
        ])
        ->assertDontSee(__('Fill in your collaboration preferences'), false);

    $user->prompts->dismissed_browse_engagements_prompt_at = now();

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Getting started'), false);

    $individual->update(['roles' => [IndividualRole::CommunityConnector->value]]);
    $user->prompts->forget('dismissed_browse_engagements_prompt_at');

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder([
            __('Getting started'),
            __('Current step'),
            __('Fill out and return your application'),
            __('Create a public page'),
            __('Next steps'),
            __('There are no next steps. After this you’ll be able to sign up for engagements!'),
            __('Completed steps'),
            __('Sign up and attend an orientation session'),
            __('Pick your role'),
        ], false)
        ->assertDontSee(__('Fill in your collaboration preferences'), false)
        ->assertDontSee(__('This will show up once you pick your role.'), false);

    $individual->update(['published_at' => now()]);

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSeeInOrder([
            __('Getting started'),
            __('Browse organizations'),
        ])
        ->assertDontSee(__('Create a public page'), false);

    $user->prompts->dismissed_browse_organizations_prompt_at = now();
    $user->save();
    $user->refresh();

    actingAs($user)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertDontSee(__('Getting started'), false);
});
