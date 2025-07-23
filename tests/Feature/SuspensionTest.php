<?php

use App\Enums\ConsultingService;
use App\Enums\ContactMethod;
use App\Enums\EngagementFormat;
use App\Enums\EngagementRecruitment;
use App\Enums\IdentityCluster;
use App\Enums\IndividualRole;
use App\Enums\MeetingType;
use App\Enums\OrganizationRole;
use App\Enums\ProvinceOrTerritory;
use App\Enums\StaffHaveLivedExperience;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Organization;
use App\Models\Scopes\ReachableIdentityScope;
use App\Models\Sector;
use App\Models\User;
use Database\Seeders\IdentitySeeder;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use Illuminate\Support\Carbon;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

beforeEach(function () {
    $this->suspendedUser = User::factory()->create(['suspended_at' => now()]);
    $this->adminUser = User::factory()->create(['context' => UserContext::Administrator->value]);

    seed(IdentitySeeder::class);
    seed(ImpactSeeder::class);
    seed(SectorSeeder::class);

    $this->participantUser = User::factory()->hasIndividual()->create();
    $this->participant = $this->participantUser->individual;

    $this->consultantUser = User::factory()
        ->hasIndividual([
            'bio' => ['en' => 'Me.'],
            'meeting_types' => [MeetingType::InPerson->value],
            'region' => ProvinceOrTerritory::NovaScotia->value,
            'roles' => [IndividualRole::AccessibilityConsultant->value],
            'locality' => 'Bridgewater',
            'consulting_services' => [ConsultingService::Analysis->value],
            'published_at' => now(),
        ])
        ->create();
    $this->consultant = $this->consultantUser->individual;

    $this->organizationUser = User::factory()->create(['context' => UserContext::Organization->value]);
    $this->organization = Organization::factory()->create([
        'contact_person_name' => $this->organizationUser->name,
        'contact_person_email' => $this->organizationUser->email,
        'about' => 'test organization about',
        'consulting_services' => [ConsultingService::Analysis->value],
        'contact_person_phone' => '4165555555',
        'extra_attributes' => [
            'has_age_brackets' => 0,
            'has_ethnoracial_identities' => 0,
            'has_gender_and_sexual_identities' => 0,
            'has_refugee_and_immigrant_constituency' => 0,
            'has_indigenous_identities' => 0,
        ],
        'locality' => 'Toronto',
        'preferred_contact_method' => ContactMethod::Email->value,
        'region' => ProvinceOrTerritory::Ontario->value,
        'roles' => [OrganizationRole::AccessibilityConsultant],
        'service_areas' => [ProvinceOrTerritory::Ontario->value],
        'staff_lived_experience' => StaffHaveLivedExperience::Yes->value,
        'published_at' => now(),
    ]);
    $this->organization->users()->attach(
        $this->organizationUser,
        ['role' => TeamRole::Administrator->value]
    );

    $this->organization->livedExperienceConstituencies()->attach(Identity::whereJsonContains('clusters', IdentityCluster::LivedExperience)->withoutGlobalScope(ReachableIdentityScope::class)->first()->id);
    $this->organization->areaTypeConstituencies()->attach(Identity::whereJsonContains('clusters', IdentityCluster::Area)->first()->id);

    $this->engagement = Engagement::factory()->create([
        'signup_by_date' => Carbon::now()->add(1, 'month')->format('Y-m-d'),
        'name' => ['en' => 'Workshop'],
        'languages' => config('locales.supported'),
        'who' => 'individuals',
        'format' => EngagementFormat::Survey->value,
        'recruitment' => EngagementRecruitment::OpenCall->value,
        'ideal_participants' => 25,
        'minimum_participants' => 15,
        'paid' => true,
        'materials_by_date' => '2022-11-01',
        'complete_by_date' => '2022-11-15',
        'document_languages' => ['en', 'fr'],
        'description' => ['en' => 'This is what we are doing'],
    ]);
    $this->project = $this->engagement->project;
    $this->regulatedOrganization = $this->project->projectable;
    $this->regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $this->project->update([
        'estimate_approved_at' => now(),
        'agreement_received_at' => now(),
        'contact_person_phone' => '4165555555',
        'contact_person_email' => $this->regulatedOrganizationUser->email,
        'contact_person_name' => $this->regulatedOrganizationUser->name,
        'contact_person_response_time' => ['en' => '48 hours'],
        'preferred_contact_method' => ContactMethod::Email->value,
        'team_trainings' => [
            [
                'date' => date('Y-m-d', time()),
                'name' => 'test training',
                'trainer_name' => 'trainer',
                'trainer_url' => 'http://example.com',
            ],
        ],
    ]);
    $this->project->impacts()->attach(Impact::first()->id);
    $this->regulatedOrganization->update([
        'about' => 'test regulated organization about',
        'accessibility_and_inclusion_links' => [
            'en' => [
                'title' => 'test title',
                'url' => 'http://example.com/en/',
            ],
        ],
        'contact_person_phone' => '4165555555',
        'contact_person_email' => $this->regulatedOrganizationUser->email,
        'contact_person_name' => $this->regulatedOrganizationUser->name,
        'locality' => 'Toronto',
        'preferred_contact_method' => ContactMethod::Email->value,
        'region' => [ProvinceOrTerritory::Ontario->value],
        'service_areas' => [ProvinceOrTerritory::Ontario->value],
        'published_at' => now(),
    ]);
    $this->regulatedOrganization->sectors()->attach(Sector::first()->id);
    $this->regulatedOrganization->users()->attach(
        $this->regulatedOrganizationUser,
        ['role' => TeamRole::Administrator->value]
    );
    $this->regulatedOrganization = $this->regulatedOrganization->fresh();

    $this->publishableModels = [
        $this->consultant,
        $this->organization,
        $this->regulatedOrganization,
        $this->project,
        $this->engagement,
    ];
});

test('suspended user cannot access others’ models', function () {
    expect($this->consultant->checkStatus('published'))->toBeTrue();
    actingAs($this->suspendedUser)->get(localized_route('individuals.show', $this->consultant))
        ->assertNotFound();

    expect($this->organization->checkStatus('published'))->toBeTrue();
    actingAs($this->suspendedUser)->get(localized_route('organizations.show', $this->organization))
        ->assertNotFound();

    expect($this->regulatedOrganization->checkStatus('published'))->toBeTrue();
    actingAs($this->suspendedUser)->get(localized_route('regulated-organizations.show', $this->regulatedOrganization))
        ->assertNotFound();

    expect($this->project->checkStatus('published'))->toBeTrue();
    actingAs($this->suspendedUser)->get(localized_route('projects.show', $this->project))
        ->assertNotFound();

    expect($this->engagement->checkStatus('published'))->toBeTrue();
    actingAs($this->suspendedUser)->get(localized_route('engagements.show', $this->engagement))
        ->assertNotFound();

    actingAs($this->suspendedUser)->get(localized_route('individuals.index'))
        ->assertForbidden();

    actingAs($this->suspendedUser)->get(localized_route('organizations.index'))
        ->assertForbidden();

    actingAs($this->suspendedUser)->get(localized_route('regulated-organizations.index'))
        ->assertForbidden();

    actingAs($this->suspendedUser)->get(localized_route('engagements.index'))
        ->assertForbidden();
});

test('suspended users can view their own models', function () {
    $this->consultantUser->update(['suspended_at' => now()]);
    $this->consultantUser = $this->consultantUser->fresh();
    $this->organizationUser->update(['suspended_at' => now()]);
    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser->update(['suspended_at' => now()]);
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    actingAs($this->consultantUser)->get(localized_route('individuals.show', $this->consultant))
        ->assertOk()
        ->assertSee('Your account has been suspended');

    actingAs($this->organizationUser)->get(localized_route('organizations.show', $this->organization))
        ->assertOk()
        ->assertSee('Your account has been suspended');

    actingAs($this->regulatedOrganizationUser)->get(localized_route('regulated-organizations.show', $this->regulatedOrganization))
        ->assertOk()
        ->assertSee('Your account has been suspended');

    actingAs($this->regulatedOrganizationUser)->get(localized_route('projects.show', $this->project))
        ->assertOk()
        ->assertSee('Your account has been suspended');

    actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.show', $this->engagement))
        ->assertOk()
        ->assertSee('Your account has been suspended');
});

test('suspended users cannot edit their own models', function () {
    $this->consultantUser->update(['suspended_at' => now()]);
    $this->consultantUser = $this->consultantUser->fresh();
    $this->organizationUser->update(['suspended_at' => now()]);
    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser->update(['suspended_at' => now()]);
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    actingAs($this->consultantUser)->get(localized_route('individuals.edit', $this->consultant))
        ->assertForbidden();

    actingAs($this->organizationUser)->get(localized_route('organizations.edit', $this->organization))
        ->assertForbidden();

    actingAs($this->regulatedOrganizationUser)->get(localized_route('regulated-organizations.edit', $this->regulatedOrganization))
        ->assertForbidden();

    actingAs($this->regulatedOrganizationUser)->get(localized_route('projects.manage', $this->project))
        ->assertForbidden();

    actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.manage', $this->engagement))
        ->assertForbidden();
});
