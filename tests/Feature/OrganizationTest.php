<?php

use App\Enums\ConsultingService;
use App\Enums\IdentityCluster;
use App\Enums\OrganizationRole;
use App\Enums\ProvinceOrTerritory;
use App\Models\Course;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Scopes\ReachableIdentityScope;
use App\Models\Sector;
use App\Models\User;
use Database\Seeders\IdentitySeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use Hearth\Models\Invitation;
use Hearth\Models\Membership;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Spatie\Translatable\Exceptions\AttributeIsNotTranslatable;
use Tests\RequestFactories\UpdateOrganizationRequestFactory;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;

test('users can create organizations', function () {
    $user = User::factory()->create(['context' => 'organization', 'locale' => 'asl']);

    actingAs($user)->get(localized_route('organizations.show-type-selection'))->assertOk();

    actingAs($user)->post(localized_route('organizations.store-type'), [
        'type' => 'representative',
    ])
        ->assertRedirect(localized_route('organizations.create'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('type', 'representative');

    actingAs($user)->get(localized_route('organizations.create'))->assertOk();

    $response = actingAs($user)->post(localized_route('organizations.create'), [
        'name' => ['en' => $user->name.' Foundation'],
        'type' => 'representative',
    ])
        ->assertSessionHasNoErrors();

    $organization = Organization::where('name->en', $user->name.' Foundation')->first();
    $response->assertRedirect(localized_route('organizations.show-role-selection', $organization));

    expect($organization->working_languages)->toContain('asl');

    actingAs($user)->get(localized_route('organizations.show-role-selection', $organization))->assertOk();
    actingAs($user)->from(localized_route('organizations.show-role-selection', $organization))->put(localized_route('organizations.save-roles', $organization), [
        'roles' => ['consultant'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));
    expect($organization->fresh()->isConsultant())->toBeTrue();

    actingAs($user)->from(localized_route('organizations.show-role-selection', $organization))->put(localized_route('organizations.save-roles', $organization), [
        'roles' => ['connector'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));
    expect($organization->fresh()->isConnector())->toBeTrue();

    actingAs($user)->from(localized_route('organizations.show-role-selection', $organization))->put(localized_route('organizations.save-roles', $organization), [
        'roles' => ['participant'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));
    expect($organization->fresh()->isParticipant())->toBeTrue();

    actingAs($user)->get(localized_route('organizations.show-role-edit', $organization))
        ->assertOk()
        ->assertSee('<input  type="checkbox" name="roles[]" id="roles-participant" value="participant" aria-describedby="roles-participant-hint" checked  />', false);

    actingAs($user)->from(localized_route('organizations.show-role-edit', $organization))->put(localized_route('organizations.save-roles', $organization), [
        'roles' => ['consultant'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));
    expect($organization->fresh()->isConsultant())->toBeTrue();

    expect($user->isMemberOf($organization))->toBeTrue();
    expect($user->memberships)->toHaveCount(1);

    actingAs($user)->get(localized_route('organizations.show-language-selection', $organization))->assertOk();

    actingAs($user)->from(localized_route('organizations.show-language-selection', $organization))->post(localized_route('organizations.store-languages', $organization), [
        'languages' => ['en', 'fr', 'iu'],
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', $organization));
});

test('users with admin role can edit and publish organizations', function () {
    $this->seed(IdentitySeeder::class);

    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create([
            'contact_person_name' => fake()->name,
            'staff_lived_experience' => 'yes',
            'preferred_contact_method' => 'email',
            'about' => 'test about',
            'region' => 'ON',
            'locality' => null,
            'service_areas' => [ProvinceOrTerritory::Ontario->value],
            'roles' => [OrganizationRole::ConsultationParticipant->value],
        ]);

    $organization->ConstituentIdentities()->attach(Identity::whereJsonContains('clusters', IdentityCluster::LivedExperience)->withoutGlobalScope(ReachableIdentityScope::class)->first()->id);
    $organization->ConstituentIdentities()->attach(Identity::whereJsonContains('clusters', IdentityCluster::Area)->first()->id);

    actingAs($user)->get(localized_route('organizations.edit', $organization))->assertOk();

    actingAs($user)->get(localized_route('organizations.show', $organization))->assertNotFound();

    $organization->update(['locality' => 'Toronto']);
    $organization->refresh();

    actingAs($user)->get(localized_route('organizations.show', $organization))->assertOk();

    UpdateOrganizationRequestFactory::new()->without(['name'])->fake();

    actingAs($user)->put(localized_route('organizations.update', $organization), [
        'name' => ['en' => $organization->name],
        'save_and_next' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', [
            'organization' => $organization,
            'step' => 2,
        ]));

    expect($organization->fresh()->social_links)->toBeArray()->toBeEmpty();

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization, 'step' => 1]))
        ->put(localized_route('organizations.update', $organization), [
            'name' => ['en' => $organization->name],
            'publish' => 1,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.show', $organization));

    expect($organization->fresh()->checkStatus('published'))->toBeTrue();

    actingAs($user)->put(localized_route('organizations.update', $organization->fresh()), [
        'name' => ['en' => $organization->name],
        'unpublish' => 1,
    ])
        ->assertSessionHasNoErrors();
    expect($organization->fresh()->checkStatus('published'))->toBeFalse();

    $organization->update([
        'languages' => null,
    ]);

    actingAs($user)->put(localized_route('organizations.update', $organization->fresh()), [
        'name' => ['en' => $organization->name],
        'publish' => 1,
    ])
        ->assertForbidden();

    expect($organization->fresh()->checkStatus('published'))->toBeFalse();
});

test('users with admin role can edit organization constituencies', function () {
    $this->seed(IdentitySeeder::class);

    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    expect($organization->hasConstituencies('areaTypeConstituencies'))->toBeNull();

    actingAs($user)->get(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]))->assertOk();

    $livedExperience = Identity::withoutGlobalScopes()->whereJsonContains('clusters', IdentityCluster::LivedExperience)->first();
    $areaType = Identity::whereJsonContains('clusters', IdentityCluster::Area)->first();
    $disabilityType = Identity::whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->first();
    $indigenousIdentity = Identity::whereJsonContains('clusters', IdentityCluster::Indigenous)->first();
    $ageBracket = Identity::whereJsonContains('clusters', IdentityCluster::Age)->first();
    $ethnoracialIdentity = Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->first();
    $genderIdentity = Identity::whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->first();

    actingAs($user)->put(localized_route('organizations.update-constituencies', $organization), [
        'disability_and_deaf' => true,
        'base_disability_type' => 'specific_disabilities',
        'disability_and_deaf_constituencies' => [],
        'has_other_disability_constituency' => true,
        'other_disability_constituency' => ['en' => 'Something else'],
        'has_indigenous_constituencies' => true,
        'indigenous_constituencies' => [$indigenousIdentity->id],
        'refugees_and_immigrants' => true,
        'has_gender_and_sexuality_constituencies' => true,
        'nb_gnc_fluid_identity' => true,
        'gender_and_sexuality_constituencies' => [$genderIdentity->id],
        'has_age_bracket_constituencies' => true,
        'age_bracket_constituencies' => [$ageBracket->id],
        'has_ethnoracial_identity_constituencies' => true,
        'ethnoracial_identity_constituencies' => [$ethnoracialIdentity->id],
        'area_type_constituencies' => [$areaType->id],
        'language_constituencies' => ['en', 'fr'],
        'staff_lived_experience' => 'prefer-not-to-answer',
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));

    $organization->refresh();

    expect($organization->disabilityAndDeafConstituencies)->toHaveCount(0);
    expect($organization->base_disability_type)->toEqual('specific_disabilities');
    expect($organization->other_disability_constituency)->toEqual('Something else');
    expect($organization->areaTypeConstituencies)->toHaveCount(1);
    expect($organization->hasConstituencies('areaTypeConstituencies'))->toBeTrue();
    expect($organization->indigenousConstituencies)->toHaveCount(1);
    expect($organization->genderAndSexualityConstituencies)->toHaveCount(4);
    expect($organization->genderDiverseConstituencies)->toHaveCount(3);
    expect($organization->ageBracketConstituencies)->toHaveCount(1);
    expect($organization->statusConstituencies)->toHaveCount(2);
    expect($organization->ethnoracialIdentityConstituencies)->toHaveCount(1);
    expect($organization->languageConstituencies)->toHaveCount(2);
    expect($organization->staff_lived_experience)->toEqual('prefer-not-to-answer');

    actingAs($user)->put(localized_route('organizations.update-constituencies', $organization), [
        'disability_and_deaf' => true,
        'base_disability_type' => 'specific_disabilities',
        'disability_and_deaf_constituencies' => [$disabilityType->id],
        'area_type_constituencies' => [$areaType->id],
        'has_indigenous_constituencies' => false,
        'refugees_and_immigrants' => false,
        'has_gender_and_sexuality_constituencies' => false,
        'has_age_bracket_constituencies' => false,
        'has_ethnoracial_identity_constituencies' => false,
        'language_constituencies' => ['en', 'fr'],
        'staff_lived_experience' => 'prefer-not-to-answer',
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));

    $organization->refresh();

    expect($organization->disabilityAndDeafConstituencies)->toHaveCount(1);
    expect($organization->base_disability_type)->toEqual('specific_disabilities');
    expect($organization->areaTypeConstituencies)->toHaveCount(1);
    expect($organization->indigenousConstituencies)->toHaveCount(0);
    expect($organization->genderIdentityConstituencies)->toHaveCount(0);
    expect($organization->ageBracketConstituencies)->toHaveCount(0);
    expect($organization->ethnoracialIdentityConstituencies)->toHaveCount(0);
    expect($organization->languageConstituencies)->toHaveCount(2);
    expect($organization->staff_lived_experience)->toEqual('prefer-not-to-answer');

    actingAs($user)->put(localized_route('organizations.update-constituencies', $organization->fresh()), [
        'disability_and_deaf' => true,
        'base_disability_type' => 'cross_disability_and_deaf',
        'area_type_constituencies' => [$areaType->id],
        'has_indigenous_constituencies' => false,
        'refugees_and_immigrants' => false,
        'has_gender_and_sexuality_constituencies' => false,
        'has_age_bracket_constituencies' => false,
        'has_ethnoracial_identity_constituencies' => false,
        'language_constituencies' => ['en', 'fr'],
        'staff_lived_experience' => 'prefer-not-to-answer',
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));

    $organization->refresh();

    expect($organization->base_disability_type)->toEqual('cross_disability_and_deaf');

    actingAs($user)->put(localized_route('organizations.update-constituencies', $organization->fresh()), [
        'lived_experience_constituencies' => [$livedExperience->id],
        'disability_and_deaf' => null,
        'area_type_constituencies' => [$areaType->id],
        'has_indigenous_constituencies' => false,
        'refugees_and_immigrants' => false,
        'has_gender_and_sexuality_constituencies' => false,
        'has_age_bracket_constituencies' => false,
        'has_ethnoracial_identity_constituencies' => false,
        'language_constituencies' => [],
        'staff_lived_experience' => 'prefer-not-to-answer',
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));

    $organization->refresh();

    expect($organization->extra_attributes->get('disability_and_deaf_constituencies'))->toBeNull();

    actingAs($user)->put(localized_route('organizations.update-constituencies', $organization->fresh()), [
        'lived_experience_constituencies' => [$livedExperience->id],
        'disability_and_deaf' => null,
        'base_disability_type' => null,
        'area_type_constituencies' => [$areaType->id],
        'has_indigenous_constituencies' => false,
        'refugees_and_immigrants' => false,
        'has_gender_and_sexuality_constituencies' => false,
        'has_age_bracket_constituencies' => false,
        'has_ethnoracial_identity_constituencies' => false,
        'language_constituencies' => [],
        'staff_lived_experience' => 'prefer-not-to-answer',
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 2]));

    $organization->refresh();

    expect($organization->extra_attributes->get('cross_disability_and_deaf_constituencies'))->toBeNull();
});

test('users with admin role can edit organization interests', function () {
    $this->seed(ImpactSeeder::class);
    $this->seed(SectorSeeder::class);

    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)->get(localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]))->assertOk();

    actingAs($user)->put(localized_route('organizations.update-interests', $organization->fresh()), [
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]));

    $organization->refresh();

    expect($organization->sectors)->toHaveCount(0);
    expect($organization->impacts)->toHaveCount(0);

    actingAs($user)->put(localized_route('organizations.update-interests', $organization->fresh()), [
        'impacts' => [Impact::inRandomOrder()->first()->id],
        'sectors' => [Sector::inRandomOrder()->first()->id],
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]));

    $organization->refresh();

    expect($organization->sectors)->toHaveCount(1);
    expect($organization->impacts)->toHaveCount(1);

    actingAs($user)->put(localized_route('organizations.update-interests', $organization->fresh()), [
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 3]));

    $organization->refresh();

    expect($organization->sectors)->toHaveCount(0);
    expect($organization->impacts)->toHaveCount(0);
});

test('users with admin role can edit organization contact information', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)->get(localized_route('organizations.edit', ['organization' => $organization, 'step' => 4]))->assertOk();

    $name = fake()->name;

    actingAs($user)->put(localized_route('organizations.update-contact-information', $organization->fresh()), [
        'contact_person_name' => $name,
        'contact_person_email' => Str::slug($name).'@'.fake()->safeEmailDomain,
        'preferred_contact_method' => 'email',
        'contact_person_vrs' => true,
        'save' => 1,
    ])->assertSessionHasErrors(['contact_person_phone' => 'Since you have indicated that your contact person needs VRS, please enter a phone number.']);

    actingAs($user)->put(localized_route('organizations.update-contact-information', $organization->fresh()), [
        'contact_person_name' => $name,
        'contact_person_email' => Str::slug($name).'@'.fake()->safeEmailDomain,
        'contact_person_phone' => '19024444444',
        'contact_person_vrs' => true,
        'preferred_contact_method' => 'email',
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 4]));

    $organization->refresh();

    expect($organization->contact_methods)->toContain('email')->toContain('phone');
    expect($organization->contact_person_vrs)->toBeTrue();

    expect($organization->routeNotificationForVonage(new \Illuminate\Notifications\Notification()))->toEqual($organization->contact_person_phone);
    expect($organization->routeNotificationForMail(new \Illuminate\Notifications\Notification()))->toEqual([$organization->contact_person_email => $organization->contact_person_name]);
    actingAs($user)->put(localized_route('organizations.update-contact-information', $organization->fresh()), [
        'contact_person_name' => $name,
        'contact_person_email' => Str::slug($name).'@'.fake()->safeEmailDomain,
        'contact_person_phone' => '19024444444',
        'preferred_contact_method' => 'email',
        'save' => 1,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.edit', ['organization' => $organization, 'step' => 4]));

    $organization->refresh();

    expect($organization->contact_methods)->toContain('email')->toContain('phone');
    expect($organization->contact_person_vrs)->toBeNull();
});

test('users without admin role cannot edit or publish organizations', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    actingAs($user)->get(localized_route('organizations.edit', $organization))->assertForbidden();

    UpdateOrganizationRequestFactory::new()->fake();

    actingAs($user)->put(localized_route('organizations.update', $organization), [
        'name' => ['en' => $organization->name],
        'locality' => 'St John\'s',
        'region' => 'NL',
    ])->assertForbidden();

    actingAs($user)->put(localized_route('organizations.update', $organization), [
        'publish' => 1,
    ])->assertForbidden();

    expect($organization->checkStatus('published'))->toBeFalse();

    $organization->publish();

    actingAs($user)->put(localized_route('organizations.update', $organization), [
        'unpublish' => 1,
    ])->assertForbidden();

    expect($organization->checkStatus('published'))->toBeTrue();
});

test('non members cannot edit or publish organizations', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $adminUser = User::factory()->create(['context' => 'organization']);

    $organization = Organization::factory()
        ->hasAttached($adminUser, ['role' => 'admin'])
        ->create();

    actingAs($user)->get(localized_route('organizations.edit', $organization))->assertForbidden();

    UpdateOrganizationRequestFactory::new()->fake();

    actingAs($user)->put(localized_route('organizations.update', $organization), [
        'name' => ['en' => $organization->name],
        'locality' => 'St John\'s',
        'region' => 'NL',
    ])->assertForbidden();

    actingAs($user)->put(localized_route('organizations.update', $organization), [
        'publish' => 1,
    ])->assertForbidden();

    expect($organization->checkStatus('published'))->toBeFalse();

    $organization->publish();

    actingAs($user)->put(localized_route('organizations.update', $organization), [
        'unpublish' => 1,
    ])->assertForbidden();

    expect($organization->checkStatus('published'))->toBeTrue();
});

test('organization pages can be published', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create([
            'about' => 'test organization about',
            'consulting_services' => [ConsultingService::Analysis->value],
            'contact_person_name' => 'contact name',
            'contact_person_phone' => '4165555555',
            'extra_attributes' => [
                'has_age_brackets' => 0,
                'has_ethnoracial_identities' => 0,
                'has_gender_and_sexual_identities' => 0,
                'has_refugee_and_immigrant_constituency' => 0,
                'has_indigenous_identities' => 0,
            ],
            'locality' => 'Toronto',
            'preferred_contact_method' => 'email',
            'region' => 'ON',
            'roles' => [OrganizationRole::AccessibilityConsultant],
            'service_areas' => [ProvinceOrTerritory::Ontario->value],
            'staff_lived_experience' => 'yes',
        ]);

    actingAs($user)->from(localized_route('organizations.show', $organization))->put(localized_route('organizations.update-publication-status', $organization), [
        'publish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.show', $organization));

    $organization->refresh();

    expect($organization->checkStatus('published'))->toBeTrue();
});

test('organization pages can be unpublished', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create([
            'about' => 'test organization about',
            'consulting_services' => [ConsultingService::Analysis->value],
            'contact_person_name' => 'contact name',
            'contact_person_phone' => '4165555555',
            'extra_attributes' => [
                'has_age_brackets' => 0,
                'has_ethnoracial_identities' => 0,
                'has_gender_and_sexual_identities' => 0,
                'has_refugee_and_immigrant_constituency' => 0,
                'has_indigenous_identities' => 0,
            ],
            'locality' => 'Toronto',
            'preferred_contact_method' => 'email',
            'region' => 'ON',
            'roles' => [OrganizationRole::AccessibilityConsultant],
            'service_areas' => [ProvinceOrTerritory::Ontario->value],
            'staff_lived_experience' => 'yes',
            'published_at' => date('Y-m-d h:i:s', time()),
        ]);

    actingAs($user)->from(localized_route('organizations.show', $organization))->put(localized_route('organizations.update-publication-status', $organization), [
        'unpublish' => true,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.show', $organization));

    $organization->refresh();

    expect($organization->checkStatus('draft'))->toBeTrue();
});

test('organization pages cannot be published by other users', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->create([
            'about' => 'test organization about',
            'consulting_services' => [ConsultingService::Analysis->value],
            'contact_person_name' => 'contact name',
            'contact_person_phone' => '4165555555',
            'extra_attributes' => [
                'has_age_brackets' => 0,
                'has_ethnoracial_identities' => 0,
                'has_gender_and_sexual_identities' => 0,
                'has_refugee_and_immigrant_constituency' => 0,
                'has_indigenous_identities' => 0,
            ],
            'locality' => 'Toronto',
            'preferred_contact_method' => 'email',
            'region' => 'ON',
            'roles' => [OrganizationRole::AccessibilityConsultant],
            'service_areas' => [ProvinceOrTerritory::Ontario->value],
            'staff_lived_experience' => 'yes',
        ]);

    actingAs($user)->put(localized_route('organizations.update-publication-status', $organization), [
        'publish' => true,
    ])->assertForbidden();

    $organization->refresh();
    expect($organization->checkStatus('draft'))->toBeTrue();
});

test('organization isPublishable()', function ($expected, $data, $connections = []) {
    $this->seed(IdentitySeeder::class);

    // fill data so that we don't hit a Database Integrity constraint violation during creation
    $organization = Organization::factory()->create();
    $organization->fill($data);

    foreach ($connections as $connection) {
        if ($connection === 'ageBracketConstituencies') {
            $organization->ConstituentIdentities()->attach(Identity::whereJsonContains('clusters', IdentityCluster::Age)->first()->id);
        }

        if ($connection === 'areaTypeConstituencies') {
            $organization->ConstituentIdentities()->attach(Identity::whereJsonContains('clusters', IdentityCluster::Area)->first()->id);
        }

        if ($connection === 'ethnoracialIdentityConstituencies') {
            $organization->ConstituentIdentities()->attach(Identity::whereJsonContains('clusters', IdentityCluster::Ethnoracial)->first()->id);
        }

        if ($connection === 'genderAndSexualityConstituencies') {
            $organization->ConstituentIdentities()->attach(Identity::whereJsonContains('clusters', IdentityCluster::Gender)->first()->id);
        }

        if ($connection === 'indigenousConstituencies') {
            $organization->ConstituentIdentities()->attach(Identity::whereJsonContains('clusters', IdentityCluster::Indigenous)->first()->id);
        }
    }

    expect($organization->isPublishable())->toBe($expected);
})->with('organizationIsPublishable');

test('organizations can be translated', function () {
    $organization = Organization::factory()->create();

    $organization->setTranslation('name', 'en', 'Name in English');
    $organization->setTranslation('name', 'fr', 'Name in French');

    expect($organization->name)->toEqual('Name in English');
    App::setLocale('fr');
    expect($organization->name)->toEqual('Name in French');

    expect($organization->getTranslation('name', 'en'))->toEqual('Name in English');
    expect($organization->getTranslation('name', 'fr'))->toEqual('Name in French');

    $translations = ['en' => 'Name in English', 'fr' => 'Name in French'];

    expect($organization->getTranslations('name'))->toEqual($translations);

    $this->expectException(AttributeIsNotTranslatable::class);
    $organization->setTranslation('locality', 'en', 'Locality in English');
});

test('users with admin role can update other member roles', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    actingAs($user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'admin',
        ])
        ->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role cannot update member roles', function () {
    $user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    actingAs($user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'admin',
        ])
        ->assertForbidden();
});

test('only administrator cannot downgrade their role', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $other_user = User::factory()->create(['context' => 'organization']);
    $yet_another_user = User::factory()->create(['context' => 'organization']);

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'admin'])
        ->hasAttached($yet_another_user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    actingAs($user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'member',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('organizations.show', $organization));

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    actingAs($other_user)
        ->from(localized_route('memberships.edit', $membership))
        ->put(localized_route('memberships.update', $membership), [
            'role' => 'member',
        ])
        ->assertSessionHasErrors(['role'])
        ->assertRedirect(localized_route('memberships.edit', $membership));
});

test('users with admin role can invite members', function () {
    $user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $organization->id,
            'invitationable_type' => get_class($organization),
            'email' => 'newuser@here.com',
            'role' => 'member',
        ])
        ->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role cannot invite members', function () {
    $user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $organization->id,
            'invitationable_type' => get_class($organization),
            'email' => 'newuser@here.com',
            'role' => 'member',
        ])
        ->assertForbidden();
});

test('users with admin role can cancel invitations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => 'me@here.com',
    ]);

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('invitations.destroy', ['invitation' => $invitation]))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role cannot cancel invitations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => 'me@here.com',
    ]);

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('invitations.destroy', ['invitation' => $invitation]))
        ->assertForbidden();
});

test('existing members cannot be invited', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'member'])
        ->create();

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->post(localized_route('invitations.create'), [
            'invitationable_id' => $organization->id,
            'invitationable_type' => get_class($organization),
            'email' => $other_user->email,
            'role' => 'member',
        ])
        ->assertSessionHasErrors(['email'])
        ->assertRedirect(localized_route('organizations.edit', $organization));
});

test('invitation can be accepted', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    actingAs($user)->get($acceptUrl)->assertRedirect(localized_route('dashboard'));

    expect($organization->fresh()->hasUserWithEmail($user->email))->toBeTrue();
});

test('invitation cannot be accepted by user with existing membership', function () {
    $user = User::factory()->create();
    Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();
    $other_organization = Organization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $other_organization->id,
        'invitationable_type' => get_class($other_organization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    actingAs($user)->from(localized_route('dashboard'))->get($acceptUrl)
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect($other_organization->fresh()->hasUserWithEmail($user->email))->toBeFalse();
});

test('invitation cannot be accepted by different user', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    actingAs($other_user)->get($acceptUrl)->assertForbidden();

    expect($organization->fresh()->hasUserWithEmail($user->email))->toBeFalse();
});

test('users with admin role can remove members', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($other_user, ['role' => 'member'])
        ->create();

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('memberships.destroy', $membership))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('users without admin role cannot remove members', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    $membership = Membership::where('user_id', $other_user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('memberships.destroy', $membership))
        ->assertForbidden();
});

test('only administrator cannot remove themself', function () {
    $user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $membership = Membership::where('user_id', $user->id)
        ->where('membershipable_type', 'App\Models\Organization')
        ->where('membershipable_id', $organization->id)
        ->first();

    actingAs($user)
        ->from(localized_route('organizations.edit', ['organization' => $organization]))
        ->delete(route('memberships.destroy', $membership))
        ->assertSessionHasErrors()
        ->assertRedirect(localized_route('organizations.edit', $organization));
});

test('users with admin role can delete organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->post(localized_route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
        'current_password' => 'password',
    ]);

    $response->assertRedirect(localized_route('dashboard'));
});

test('users with admin role cannot delete organizations with wrong password', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $response = $this->post(localized_route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
        'current_password' => 'wrong_password',
    ]);

    $response->assertSessionHasErrors();
    $response->assertRedirect(localized_route('organizations.edit', $organization));
});

test('users without admin role cannot delete organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'member'])
        ->create();

    $response = $this->post(localized_route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->from(localized_route('organizations.edit', $organization))->delete(localized_route('organizations.destroy', $organization), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('non members cannot delete organizations', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->create();

    $other_organization = Organization::factory()
        ->hasAttached($other_user, ['role' => 'admin'])
        ->create();

    $response = $this->post(localized_route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response = $this->from(localized_route('organizations.edit', $other_organization))->delete(localized_route('organizations.destroy', $other_organization), [
        'current_password' => 'password',
    ]);

    $response->assertForbidden();
});

test('users can not view organizations if they are not oriented', function () {
    $pendingUser = User::factory()->create(['oriented_at' => null]);
    actingAs($pendingUser)->get(localized_route('organizations.index'))->assertForbidden();

    $pendingUser->update(['oriented_at' => now()]);
    actingAs($pendingUser)->get(localized_route('organizations.index'))->assertOk();
});

test('organization or regulated organization users can not view organizations if they are not oriented', function () {
    $organizationUser = User::factory()->create(['context' => 'organization', 'oriented_at' => null]);
    $organization = Organization::factory()->hasAttached($organizationUser, ['role' => 'admin'])->create(['oriented_at' => null]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('organizations.index'))
        ->assertForbidden();

    $organization->update(['oriented_at' => now()]);
    $organizationUser->refresh();

    actingAs($organizationUser)->get(localized_route('organizations.index'))
        ->assertOk();

    $regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization', 'oriented_at' => null]);
    $regulatedOrganization = RegulatedOrganization::factory()->hasAttached($regulatedOrganizationUser, ['role' => 'admin'])->create(['oriented_at' => null]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('organizations.index'))
        ->assertForbidden();

    $regulatedOrganization->update(['oriented_at' => now()]);
    $regulatedOrganizationUser->refresh();

    actingAs($regulatedOrganizationUser)->get(localized_route('organizations.index'))
        ->assertOk();
});

test('users can view organizations', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create(['working_languages' => ['en', 'asl'], 'published_at' => now(), 'service_areas' => ['NS']]);

    actingAs($user)->get(localized_route('organizations.index'))->assertOk();

    actingAs($user)->get(localized_route('organizations.show', $organization))->assertOk();
});

test('guests cannot view organizations', function () {
    $organization = Organization::factory()->create();

    $response = $this->get(localized_route('organizations.index'));
    $response->assertRedirect(localized_route('login'));

    $response = $this->get(localized_route('organizations.show', $organization));
    $response->assertRedirect(localized_route('login'));
});

test('organizational relationships to projects can be derived from both projects and engagements', function () {
    $organization = Organization::factory()->create(['roles' => ['consultant', 'connector', 'participant']]);

    $organization->refresh();

    $connectingEngagement = Engagement::factory()->create([
        'organizational_connector_id' => $organization->id,
    ]);

    expect($connectingEngagement->organizationalConnector->id)->toEqual($organization->id);

    $connectingEngagementProject = $connectingEngagement->project;

    $participatingEngagement = Engagement::factory()->create();

    $participatingEngagement->organization()->associate($organization);
    $participatingEngagement->save();
    $participatingEngagement = $participatingEngagement->fresh();

    $participatingEngagementProject = $participatingEngagement->project;

    expect($organization->contractedProjects->pluck('id')->toArray())
        ->toHaveCount(1)
        ->toContain($connectingEngagementProject->id);

    expect($organization->participatingProjects->pluck('id')->toArray())
        ->toHaveCount(1)
        ->toContain($participatingEngagementProject->id);

    expect($participatingEngagementProject->organizationalParticipants->pluck('id')->toArray())
        ->toHaveCount(1)
        ->toContain($organization->id);
});

test('organizations projects functions based on project state', function () {
    $organization = Organization::factory()->create([
        'roles' => ['consultant', 'connector', 'participant'],
        'published_at' => now(),
    ]);

    $draftProject = Project::factory()->create(['published_at' => null]);
    $inProgressProject = Project::factory()->create();
    $upcomingProject = Project::factory()->create([
        'start_date' => now()->addMonth(),
        'end_date' => now()->addMonths(12),
    ]);
    $completedProject = Project::factory()->create([
        'start_date' => now()->subMonths(12),
        'end_date' => now()->subMonth(),
    ]);

    $organization->projects()->saveMany([
        $draftProject,
        $inProgressProject,
        $upcomingProject,
        $completedProject,
    ]);

    expect($organization->projects)->toHaveCount(4);
    expect($organization->projects->modelKeys())->toContain($draftProject->id, $inProgressProject->id, $upcomingProject->id, $completedProject->id);

    expect($organization->draftProjects)->toHaveCount(1);
    expect($organization->draftProjects->modelKeys())->toContain($draftProject->id);

    expect($organization->publishedProjects)->toHaveCount(3);
    expect($organization->publishedProjects->modelKeys())->toContain($inProgressProject->id, $upcomingProject->id, $completedProject->id);

    expect($organization->inProgressProjects)->toHaveCount(2);
    expect($organization->inProgressProjects->modelKeys())->toContain($draftProject->id, $inProgressProject->id);

    expect($organization->upcomingProjects)->toHaveCount(1);
    expect($organization->upcomingProjects->modelKeys())->toContain($upcomingProject->id);

    expect($organization->completedProjects)->toHaveCount(1);
    expect($organization->completedProjects->modelKeys())->toContain($completedProject->id);
});

test('organizations have slugs in both languages even if only one is provided', function () {
    $organization = Organization::factory()->create();
    expect($organization->getTranslation('slug', 'fr', false))
        ->toEqual($organization->getTranslation('slug', 'en', false));

    $organization = Organization::factory()->create(['name' => ['fr' => 'Mon entreprise']]);
    expect($organization->getTranslation('slug', 'en', false))
        ->toEqual($organization->getTranslation('slug', 'fr', false));
});

test('organization can have many courses', function () {
    $organization = Organization::factory()->create();

    $courseOne = Course::factory()->for($organization)->create();
    $courseOne = Course::factory()->for($organization)->create();

    expect($organization->courses->contains($courseOne))->toBeTrue();
    expect($organization->courses->contains($courseOne))->toBeTrue();
});

test('Organization isInProgress()', function ($data, $withConstituentIdentity, $expected) {
    $this->seed(IdentitySeeder::class);
    $organization = Organization::factory()
        ->create($data);

    if ($withConstituentIdentity) {
        $organization->ConstituentIdentities()->attach(Identity::whereJsonContains('clusters', IdentityCluster::Area)->first()->id);
    }

    expect($organization->isInProgress())->toEqual($expected);
})->with('organizationIsInProgress');

test('organization status checks return expected state', function () {
    $organization = Organization::factory()->create([
        'published_at' => null,
        'oriented_at' => null,
        'validated_at' => null,
        'suspended_at' => null,
        'dismissed_invite_prompt_at' => null,
    ]);

    expect($organization->checkStatus('draft'))->toBeTrue();
    expect($organization->checkStatus('published'))->toBeFalse();
    expect($organization->checkStatus('pending'))->toBeTrue();
    expect($organization->checkStatus('approved'))->toBeFalse();
    expect($organization->checkStatus('suspended'))->toBeFalse();
    expect($organization->checkStatus('dismissedInvitePrompt'))->toBeFalse();

    $organization->published_at = now();
    $organization->save();

    expect($organization->checkStatus('draft'))->toBeFalse();
    expect($organization->checkStatus('published'))->toBeTrue();

    $organization->oriented_at = now();
    $organization->save();

    expect($organization->checkStatus('pending'))->toBeFalse();
    expect($organization->checkStatus('approved'))->toBeFalse();

    $organization->validated_at = now();
    $organization->save();

    expect($organization->checkStatus('pending'))->toBeFalse();
    expect($organization->checkStatus('approved'))->toBeTrue();

    $organization->suspended_at = now();
    $organization->save();

    expect($organization->checkStatus('suspended'))->toBeTrue();

    $organization->dismissed_invite_prompt_at = now();
    $organization->save();

    expect($organization->checkStatus('dismissedInvitePrompt'))->toBeTrue();
});
