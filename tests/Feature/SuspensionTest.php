<?php

use App\Enums\ConsultingService;
use App\Enums\IdentityCluster;
use App\Enums\OrganizationRole;
use App\Enums\ProvinceOrTerritory;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Impact;
use App\Models\Organization;
use App\Models\Sector;
use App\Models\User;
use Database\Seeders\ImpactSeeder;
use Database\Seeders\SectorSeeder;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->suspendedUser = User::factory()->create(['suspended_at' => now()]);
    $this->adminUser = User::factory()->create(['context' => 'administrator']);

    $this->seed(IdentitySeeder::class);
    $this->seed(ImpactSeeder::class);
    $this->seed(SectorSeeder::class);

    $this->participantUser = User::factory()->create();
    $this->participantUser->individual->update([
        'roles' => ['participant'],
    ]);
    $this->participant = $this->participantUser->individual->fresh();

    $this->consultantUser = User::factory()->create();
    $this->consultantUser->individual->update([
        'bio' => ['en' => 'Me.'],
        'meeting_types' => ['in_person'],
        'region' => 'NS',
        'roles' => ['consultant'],
        'locality' => 'Bridgewater',
        'consulting_services' => ['analysis'],
        'published_at' => now(),
    ]);
    $this->consultant = $this->consultantUser->individual->fresh();

    $this->organizationUser = User::factory()->create(['context' => 'organization']);
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
        'preferred_contact_method' => 'email',
        'region' => 'ON',
        'roles' => [OrganizationRole::AccessibilityConsultant],
        'service_areas' => [ProvinceOrTerritory::Ontario->value],
        'staff_lived_experience' => 'yes',
        'published_at' => now(),
    ]);
    $this->organization->users()->attach(
        $this->organizationUser,
        ['role' => 'admin']
    );

    $this->organization->livedExperienceConstituencies()->attach(Identity::firstWhere('cluster', IdentityCluster::LivedExperience)->id);
    $this->organization->areaTypeConstituencies()->attach(Identity::firstWhere('cluster', IdentityCluster::Area)->id);

    $this->engagement = Engagement::factory()->create([
        'signup_by_date' => Carbon::now()->add(1, 'month')->format('Y-m-d'),
        'name' => ['en' => 'Workshop'],
        'languages' => config('locales.supported'),
        'who' => 'individuals',
        'format' => 'survey',
        'recruitment' => 'open-call',
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
    $this->regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $this->project->update([
        'estimate_approved_at' => now(),
        'agreement_received_at' => now(),
        'contact_person_phone' => '4165555555',
        'contact_person_email' => $this->regulatedOrganizationUser->email,
        'contact_person_name' => $this->regulatedOrganizationUser->name,
        'contact_person_response_time' => ['en' => '48 hours'],
        'preferred_contact_method' => 'email',
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
        'preferred_contact_method' => 'email',
        'region' => [ProvinceOrTerritory::Ontario->value],
        'service_areas' => [ProvinceOrTerritory::Ontario->value],
        'published_at' => now(),
    ]);
    $this->regulatedOrganization->sectors()->attach(Sector::first()->id);
    $this->regulatedOrganization->users()->attach(
        $this->regulatedOrganizationUser,
        ['role' => 'admin']
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
    $response = $this->actingAs($this->suspendedUser)->get(localized_route('individuals.show', $this->consultant));
    $response->assertNotFound();

    expect($this->organization->checkStatus('published'))->toBeTrue();
    $response = $this->actingAs($this->suspendedUser)->get(localized_route('organizations.show', $this->organization));
    $response->assertNotFound();

    expect($this->regulatedOrganization->checkStatus('published'))->toBeTrue();
    $response = $this->actingAs($this->suspendedUser)->get(localized_route('regulated-organizations.show', $this->regulatedOrganization));
    $response->assertNotFound();

    expect($this->project->checkStatus('published'))->toBeTrue();
    $response = $this->actingAs($this->suspendedUser)->get(localized_route('projects.show', $this->project));
    $response->assertNotFound();

    expect($this->engagement->checkStatus('published'))->toBeTrue();
    $response = $this->actingAs($this->suspendedUser)->get(localized_route('engagements.show', $this->engagement));
    $response->assertNotFound();

    $response = $this->actingAs($this->suspendedUser)->get(localized_route('individuals.index'));
    $response->assertForbidden();

    $response = $this->actingAs($this->suspendedUser)->get(localized_route('organizations.index'));
    $response->assertForbidden();

    $response = $this->actingAs($this->suspendedUser)->get(localized_route('regulated-organizations.index'));
    $response->assertForbidden();

    $response = $this->actingAs($this->suspendedUser)->get(localized_route('projects.all-projects'));
    $response->assertForbidden();
});

test('suspended users can view their own models', function () {
    $this->consultantUser->update(['suspended_at' => now()]);
    $this->consultantUser = $this->consultantUser->fresh();
    $this->organizationUser->update(['suspended_at' => now()]);
    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser->update(['suspended_at' => now()]);
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    $response = $this->actingAs($this->consultantUser)->get(localized_route('individuals.show', $this->consultant));
    $response->assertOk();
    $response->assertSee('Your account has been suspended');

    $response = $this->actingAs($this->organizationUser)->get(localized_route('organizations.show', $this->organization));
    $response->assertOk();
    $response->assertSee('Your account has been suspended');

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('regulated-organizations.show', $this->regulatedOrganization));
    $response->assertOk();
    $response->assertSee('Your account has been suspended');

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('projects.show', $this->project));
    $response->assertOk();
    $response->assertSee('Your account has been suspended');

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.show', $this->engagement));
    $response->assertOk();
    $response->assertSee('Your account has been suspended');
});

test('suspended users cannot edit their own models', function () {
    $this->consultantUser->update(['suspended_at' => now()]);
    $this->consultantUser = $this->consultantUser->fresh();
    $this->organizationUser->update(['suspended_at' => now()]);
    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser->update(['suspended_at' => now()]);
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    $response = $this->actingAs($this->consultantUser)->get(localized_route('individuals.edit', $this->consultant));
    $response->assertForbidden();

    $response = $this->actingAs($this->organizationUser)->get(localized_route('organizations.edit', $this->organization));
    $response->assertForbidden();

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('regulated-organizations.edit', $this->regulatedOrganization));
    $response->assertForbidden();

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('projects.manage', $this->project));
    $response->assertForbidden();

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.manage', $this->engagement));
    $response->assertForbidden();
});
