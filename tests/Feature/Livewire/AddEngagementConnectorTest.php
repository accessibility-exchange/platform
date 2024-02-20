<?php

use App\Enums\IdentityCluster;
use App\Livewire\AddEngagementConnector;
use App\Models\Engagement;
use App\Models\Identity;
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

test('unregistered individual can be invited to be an engagement’s community connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    actingAs($user)->get(localized_route('engagements.add-connector', $engagement))
        ->assertOk();

    actingAs($user);

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => 'individual',
        'email' => 'connector@example.com',
    ])
        ->assertSet('project', $engagement->project)
        ->call('inviteConnector');

    $engagement = $engagement->fresh();

    expect($engagement->invitations)->toHaveCount(1);
    expect($engagement->invitations->first()->role)->toEqual('connector');
    expect($engagement->invitations->first()->type)->toEqual('individual');

    actingAs($user)->get(localized_route('engagements.manage-connector', $engagement))
        ->assertOk()
        ->assertSee('connector@example.com');
});

test('registered individual can be invited to be an engagement’s community connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $individualUser = User::factory()->create();
    $individual = $individualUser->individual;
    $individual->update(['roles' => ['consultant'], 'region' => 'NS', 'locality' => 'Bridgewater']);
    $individual->publish();

    $individual = $individual->fresh();

    actingAs($user)->get(localized_route('engagements.add-connector', $engagement))
        ->assertOk();

    actingAs($user);

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => 'individual',
        'email' => $individualUser->email,
    ])
        ->assertSet('project', $engagement->project)
        ->call('inviteConnector')
        ->assertHasErrors('email');

    $individual->update(['roles' => ['connector']]);
    $individual = $individual->fresh();

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => 'individual',
        'email' => $individualUser->email,
    ])
        ->assertSet('project', $engagement->project)
        ->call('inviteConnector')
        ->assertHasNoErrors('email');

    $engagement = $engagement->fresh();

    expect($engagement->invitations)->toHaveCount(1);
    expect($engagement->invitations->first()->role)->toEqual('connector');
    expect($engagement->invitations->first()->type)->toEqual('individual');

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
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $organization = Organization::factory()->create(['roles' => ['consultant'], 'published_at' => now(), 'region' => 'AB', 'locality' => 'Medicine Hat']);

    $organizationUser = User::factory()->create(['context' => 'organization']);

    $organization->users()->attach(
        $organizationUser,
        ['role' => 'admin']
    );

    actingAs($user)->get(localized_route('engagements.add-connector', $engagement))
        ->assertOk();

    actingAs($user);

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => 'organization',
    ])
        ->assertSet('project', $engagement->project)
        ->assertDontSee($organization->name)
        ->call('inviteConnector')
        ->assertHasErrors('organization');

    $organization->update(['roles' => ['consultant', 'connector']]);
    $organization = $organization->fresh();

    $consultantInvitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $engagement->id,
        'role' => 'consultant',
        'type' => 'organization',
        'email' => $organization->contact_person_email,
    ]);

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => 'organization',
        'organization' => $organization->id,
        'organizations' => Options::forModels(Organization::query()->whereJsonContains('roles', 'connector'))->nullable(__('Choose a community organization…'))->toArray(),
    ])
        ->assertSet('project', $engagement->project)
        ->assertSee($organization->name)
        ->call('inviteConnector')
        ->assertHasErrors('email');

    $consultantInvitation->delete();

    livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => 'organization',
        'organization' => $organization->id,
        'organizations' => Options::forModels(Organization::query()->whereJsonContains('roles', 'connector'))->nullable(__('Choose a community organization…'))->toArray(),
    ])
        ->assertSet('project', $engagement->project)
        ->assertSee($organization->name)
        ->call('inviteConnector')
        ->assertHasNoErrors('organization');

    $engagement = $engagement->fresh();

    expect($engagement->invitations)->toHaveCount(1);
    expect($engagement->invitations->first()->role)->toEqual('connector');
    expect($engagement->invitations->first()->type)->toEqual('organization');

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

    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);
    $areaIdentity = Identity::whereJsonContains('clusters', IdentityCluster::Area)->first();
    $fro = $engagement->project->projectable;
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $fro->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $orgNotOriented = Organization::factory()
        ->hasAttached(
            User::factory()->state(['context' => 'organization']),
            ['role' => 'admin']
        )
        ->create([
            'roles' => ['connector'],
            'oriented_at' => null,
        ]);

    $orgNotPublishable = Organization::factory()
        ->hasAttached(
            User::factory()->state(['context' => 'organization']),
            ['role' => 'admin']
        )
        ->create([
            'roles' => ['connector'],
        ]);

    $orgSuspended = Organization::factory()
        ->hasAttached(
            User::factory()->state(['context' => 'organization']),
            ['role' => 'admin']
        )
        ->create([
            'roles' => ['connector'],
            'published_at' => now(),
            'about' => 'About',
            'contact_person_name' => 'Contact',
            'region' => 'AB',
            'locality' => 'Medicine Hat',
            'preferred_contact_method' => 'email',
            'staff_lived_experience' => false,
            'suspended_at' => now(),
        ]);
    $orgSuspended->constituentIdentities()->attach($areaIdentity);

    $organization = Organization::factory()
        ->hasAttached(
            User::factory()->state(['context' => 'organization']),
            ['role' => 'admin']
        )
        ->create([
            'roles' => ['connector'],
            'published_at' => now(),
            'about' => 'About',
            'contact_person_name' => 'Contact',
            'region' => 'AB',
            'locality' => 'Medicine Hat',
            'preferred_contact_method' => 'email',
            'staff_lived_experience' => false,
        ]);
    $organization->constituentIdentities()->attach($areaIdentity);

    actingAs($fro->users->first())->
        livewire(AddEngagementConnector::class, [
            'engagement' => $engagement,
            'who' => 'organization',
        ])
            ->assertOk()
            ->assertDontSee($orgNotOriented->name)
            ->assertDontSee($orgNotPublishable->name)
            ->assertDontSee($orgSuspended->name)
            ->assertSee($organization->name);
});
