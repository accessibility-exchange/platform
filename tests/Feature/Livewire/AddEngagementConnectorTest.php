<?php

use App\Enums\ContactMethod;
use App\Enums\EngagementRecruitment;
use App\Enums\IdentityCluster;
use App\Enums\IndividualRole;
use App\Enums\ProvinceOrTerritory;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Livewire\AddEngagementConnector;
use App\Models\Engagement;
use App\Models\Identity;
use App\Models\Individual;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\IndividualContractorInvited;
use App\Notifications\OrganizationalContractorInvited;
use Illuminate\Support\Facades\URL;
use Spatie\LaravelOptions\Options;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;

pest()->group('engagement');

test('unregistered individual can be invited to be an engagement’s community connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => EngagementRecruitment::CommunityConnector->value]);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => TeamRole::Administrator->value]
    );

    actingAs($user)->get(localized_route('engagements.add-connector', $engagement))
        ->assertOk();

    actingAs($user);

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => UserContext::Individual->value,
        'email' => 'connector@example.com',
    ])
        ->assertSet('project', $engagement->project)
        ->call('inviteConnector');

    $engagement = $engagement->fresh();

    expect($engagement->invitations)->toHaveCount(1);
    expect($engagement->invitations->first()->role)->toEqual(IndividualRole::CommunityConnector->value);
    expect($engagement->invitations->first()->type)->toEqual(UserContext::Individual->value);

    actingAs($user)->get(localized_route('engagements.manage-connector', $engagement))
        ->assertOk()
        ->assertSee('connector@example.com');
});

test('registered individual can be invited to be an engagement’s community connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => EngagementRecruitment::CommunityConnector->value]);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => TeamRole::Administrator->value]
    );

    $individual = Individual::factory()->create([
        'roles' => [IndividualRole::AccessibilityConsultant->value],
        'region' => ProvinceOrTerritory::NovaScotia->value,
        'locality' => 'Bridgewater',
    ]);

    $individualUser = $individual->user;

    actingAs($user)->get(localized_route('engagements.add-connector', $engagement))
        ->assertOk();

    actingAs($user);

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => UserContext::Individual->value,
        'email' => $individualUser->email,
    ])
        ->assertSet('project', $engagement->project)
        ->call('inviteConnector')
        ->assertHasErrors('email');

    $individual->update(['roles' => [IndividualRole::CommunityConnector->value]]);
    $individual = $individual->fresh();

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => UserContext::Individual->value,
        'email' => $individualUser->email,
    ])
        ->assertSet('project', $engagement->project)
        ->call('inviteConnector')
        ->assertHasNoErrors('email');

    $engagement = $engagement->fresh();

    expect($engagement->invitations)->toHaveCount(1);
    expect($engagement->invitations->first()->role)->toEqual(IndividualRole::CommunityConnector->value);
    expect($engagement->invitations->first()->type)->toEqual(UserContext::Individual->value);

    actingAs($user)->get(localized_route('engagements.manage-connector', $engagement))
        ->assertOk()
        ->assertSee($individual->name);

    $notification = new IndividualContractorInvited($engagement->invitations->first());
    $this->assertStringContainsString('You have been invited', $notification->toMail($individualUser)->render());
    $this->assertStringContainsString('You have been invited', $notification->toVonage($individualUser)->content);

    expect($individualUser->notifications)->toHaveCount(1);
    $databaseNotification = $individualUser->notifications->first();

    actingAs($individualUser)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee('Accept')
        ->assertSee(URL::signedRoute('contractor-invitations.accept', $engagement->invitations->first()));

    actingAs($individualUser)->get(URL::signedRoute('contractor-invitations.accept', $engagement->invitations->first()))
        ->assertRedirect(localized_route('dashboard'));

    expect($engagement->fresh()->connector->id)->toEqual($individual->id);
    assertModelMissing($databaseNotification);
});

test('registered organization can be invited to be an engagement’s community connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => EngagementRecruitment::CommunityConnector->value]);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => TeamRole::Administrator->value]
    );

    $organization = Organization::factory()->create(['roles' => [IndividualRole::AccessibilityConsultant->value], 'published_at' => now(), 'region' => ProvinceOrTerritory::Alberta->value, 'locality' => 'Medicine Hat']);

    $organizationUser = User::factory()->create(['context' => UserContext::Organization->value]);

    $organization->users()->attach(
        $organizationUser,
        ['role' => TeamRole::Administrator->value]
    );

    actingAs($user)->get(localized_route('engagements.add-connector', $engagement))
        ->assertOk();

    actingAs($user);

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => UserContext::Organization->value,
    ])
        ->assertSet('project', $engagement->project)
        ->assertDontSee($organization->name)
        ->call('inviteConnector')
        ->assertHasErrors('organization');

    $organization->update(['roles' => [
        IndividualRole::AccessibilityConsultant->value,
        IndividualRole::CommunityConnector->value,
    ]]);
    $organization = $organization->fresh();

    $consultantInvitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => IndividualRole::AccessibilityConsultant->value,
        'type' => UserContext::Organization->value,
        'email' => $organization->contact_person_email,
    ]);

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => UserContext::Organization->value,
        'organization' => $organization->id,
        'organizations' => Options::forModels(Organization::query()->whereJsonContains('roles', IndividualRole::CommunityConnector->value))->nullable(__('Choose a community organization…'))->toArray(),
    ])
        ->assertSet('project', $engagement->project)
        ->assertSee($organization->name)
        ->call('inviteConnector')
        ->assertHasErrors('email');

    $consultantInvitation->delete();

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => UserContext::Organization->value,
        'organization' => $organization->id,
        'organizations' => Options::forModels(Organization::query()->whereJsonContains('roles', IndividualRole::CommunityConnector->value))->nullable(__('Choose a community organization…'))->toArray(),
    ])
        ->assertSet('project', $engagement->project)
        ->assertSee($organization->name)
        ->call('inviteConnector')
        ->assertHasNoErrors('organization');

    $engagement = $engagement->fresh();

    expect($engagement->invitations)->toHaveCount(1);
    expect($engagement->invitations->first()->role)->toEqual(IndividualRole::CommunityConnector->value);
    expect($engagement->invitations->first()->type)->toEqual(UserContext::Organization->value);

    actingAs($user)->get(localized_route('engagements.manage-connector', $engagement))
        ->assertOk()
        ->assertSee($organization->name);

    $notification = new OrganizationalContractorInvited($engagement->invitations->first());
    $this->assertStringContainsString('Your organization has been invited', $notification->toMail($organization)->render());
    $this->assertStringContainsString('Your organization has been invited', $notification->toVonage($organization)->content);

    expect($organization->notifications)->toHaveCount(1);
    $databaseNotification = $organization->notifications->first();

    actingAs($organizationUser)->get(localized_route('dashboard'))
        ->assertOk()
        ->assertSee('Accept')
        ->assertSee(URL::signedRoute('contractor-invitations.accept', $engagement->invitations->first()));

    actingAs($organizationUser)->get(URL::signedRoute('contractor-invitations.accept', $engagement->invitations->first()))
        ->assertRedirect(localized_route('dashboard'));

    expect($engagement->fresh()->organizationalConnector->id)->toEqual($organization->id);
    assertModelMissing($databaseNotification);
});

test('only publishable orgs are available to choose as a community connector', function () {
    seed(IdentitySeeder::class);

    $engagement = Engagement::factory()->create(['recruitment' => EngagementRecruitment::CommunityConnector->value]);
    $areaIdentity = Identity::whereJsonContains('clusters', IdentityCluster::Area)->first();
    $fro = $engagement->project->projectable;
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $fro->users()->attach(
        $user,
        ['role' => TeamRole::Administrator->value]
    );

    $orgNotOriented = Organization::factory()
        ->hasAttached(
            User::factory()->state(['context' => UserContext::Organization->value]),
            ['role' => TeamRole::Administrator->value]
        )
        ->create([
            'roles' => [IndividualRole::CommunityConnector->value],
            'oriented_at' => null,
        ]);

    $orgNotPublishable = Organization::factory()
        ->hasAttached(
            User::factory()->state(['context' => UserContext::Organization->value]),
            ['role' => TeamRole::Administrator->value]
        )
        ->create([
            'roles' => [IndividualRole::CommunityConnector->value],
        ]);

    $orgSuspended = Organization::factory()
        ->hasAttached(
            User::factory()->state(['context' => UserContext::Organization->value]),
            ['role' => TeamRole::Administrator->value]
        )
        ->create([
            'roles' => [IndividualRole::CommunityConnector->value],
            'published_at' => now(),
            'about' => 'About',
            'contact_person_name' => 'Contact',
            'region' => ProvinceOrTerritory::Alberta->value,
            'locality' => 'Medicine Hat',
            'preferred_contact_method' => ContactMethod::Email->value,
            'staff_lived_experience' => false,
            'suspended_at' => now(),
        ]);
    $orgSuspended->constituentIdentities()->attach($areaIdentity);

    $organization = Organization::factory()
        ->hasAttached(
            User::factory()->state(['context' => UserContext::Organization->value]),
            ['role' => TeamRole::Administrator->value]
        )
        ->create([
            'roles' => [IndividualRole::CommunityConnector->value],
            'published_at' => now(),
            'about' => 'About',
            'contact_person_name' => 'Contact',
            'region' => ProvinceOrTerritory::Alberta->value,
            'locality' => 'Medicine Hat',
            'preferred_contact_method' => ContactMethod::Email->value,
            'staff_lived_experience' => false,
        ]);
    $organization->constituentIdentities()->attach($areaIdentity);

    actingAs($fro->users->first())->
        livewire(AddEngagementConnector::class, [
            'engagement' => $engagement,
            'who' => UserContext::Organization->value,
        ])
            ->assertOk()
            ->assertDontSee($orgNotOriented->name)
            ->assertDontSee($orgNotPublishable->name)
            ->assertDontSee($orgSuspended->name)
            ->assertSee($organization->name);
});
