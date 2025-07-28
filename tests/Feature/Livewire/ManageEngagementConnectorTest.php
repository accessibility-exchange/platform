<?php

use App\Enums\EngagementRecruitment;
use App\Enums\IndividualRole;
use App\Enums\OrganizationRole;
use App\Enums\ProvinceOrTerritory;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Livewire\ManageEngagementConnector;
use App\Models\Engagement;
use App\Models\Individual;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelMissing;
use function Pest\Livewire\livewire;

pest()->group('engagement');

test('engagement consultant management page can be rendered and connector can be sought', function () {
    $engagement = Engagement::factory()->create(['recruitment' => EngagementRecruitment::CommunityConnector->value]);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => TeamRole::Administrator->value]
    );

    actingAs($user)->get(localized_route('engagements.manage-connector', $engagement))
        ->assertOk();

    livewire(ManageEngagementConnector::class, ['engagement' => $engagement])
        ->assertSet('project', $engagement->project)
        ->assertSet('seeking_community_connector', false)
        ->set('seeking_community_connector', true)
        ->call('updateStatus');

    $engagement = $engagement->fresh();

    expect($engagement->extra_attributes->get('seeking_community_connector'))->toBeTrue();
});

test('connector invitations can be cancelled', function () {
    $engagement = Engagement::factory()->create(['recruitment' => EngagementRecruitment::CommunityConnector->value]);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => TeamRole::Administrator->value]
    );

    $user = User::factory()
        ->has(Individual::factory()->state([
            'roles' => [IndividualRole::CommunityConnector->value],
            'region' => ProvinceOrTerritory::NovaScotia->value,
            'locality' => 'Bridgewater',
        ]))
        ->create();

    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => IndividualRole::CommunityConnector->value,
        'type' => UserContext::Individual->value,
        'email' => $user->email,
    ]);

    actingAs($regulatedOrganizationUser);

    livewire(ManageEngagementConnector::class, [
        'engagement' => $engagement,
    ])
        ->assertSee($user->name)
        ->assertSee('Cancel')
        ->call('cancelInvitation');

    assertModelMissing($invitation);
});

test('individual connector can be removed', function () {
    $engagement = Engagement::factory()->create(['recruitment' => EngagementRecruitment::CommunityConnector->value]);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => TeamRole::Administrator->value]
    );

    $individual = Individual::factory()->create([
        'roles' => [IndividualRole::CommunityConnector->value],
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'locality' => 'Bridgewater',
    ]);

    $engagement->connector()->associate($individual);

    actingAs($regulatedOrganizationUser)
        ->livewire(ManageEngagementConnector::class, [
            'engagement' => $engagement,
        ])
        ->assertSee($individual->name)
        ->assertSee('Remove')
        ->call('removeConnector');

    $engagement = $engagement->fresh();
    expect($engagement->connector)->toBeNull();
});

test('organizational connector can be removed', function () {
    $engagement = Engagement::factory()->create(['recruitment' => EngagementRecruitment::CommunityConnector->value]);
    $project = $engagement->project;
    $project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $regulatedOrganization = $project->projectable;
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization->users()->attach(
        $regulatedOrganizationUser,
        ['role' => TeamRole::Administrator->value]
    );

    $organization = Organization::factory()->create([
        'roles' => [OrganizationRole::AccessibilityConsultant->value],
        'published_at' => now(),
        'region' => ProvinceOrTerritory::Alberta->value,
        'locality' => 'Medicine Hat',
    ]);

    $engagement->organizationalConnector()->associate($organization);

    actingAs($regulatedOrganizationUser)->
    livewire(ManageEngagementConnector::class, [
        'engagement' => $engagement,
    ])
        ->assertSee($organization->name)
        ->assertSee('Remove')
        ->call('removeConnector');

    $engagement = $engagement->fresh();
    expect($engagement->organizationalConnector)->toBeNull();
});
