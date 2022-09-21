<?php

use App\Http\Livewire\AddEngagementConnector;
use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\IndividualContractorInvited;
use Spatie\LaravelOptions\Options;

test('unregistered individual can be invited to be an engagement’s community connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $response = $this->actingAs($user)->get(localized_route('engagements.add-connector', $engagement));
    $response->assertOk();

    $this->actingAs($user);

    $this->livewire(AddEngagementConnector::class, [
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

    $response = $this->actingAs($user)->get(localized_route('engagements.manage-connector', $engagement));
    $response->assertOk();
    $response->assertSee('connector@example.com');
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
    $individual->update(['roles' => ['consultant']]);
    $individual->publish();

    $individual = $individual->fresh();

    $response = $this->actingAs($user)->get(localized_route('engagements.add-connector', $engagement));
    $response->assertOk();

    $this->actingAs($user);

    $this->livewire(AddEngagementConnector::class, [
        'engagement' => $engagement,
        'who' => 'individual',
        'email' => $individualUser->email,
    ])
        ->assertSet('project', $engagement->project)
        ->call('inviteConnector')
        ->assertHasErrors('email');

    $individual->update(['roles' => ['connector']]);
    $individual = $individual->fresh();

    $this->livewire(AddEngagementConnector::class, [
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

    $response = $this->actingAs($user)->get(localized_route('engagements.manage-connector', $engagement));
    $response->assertOk();
    $response->assertSee($individual->name);

    $notification = new IndividualContractorInvited($engagement->invitations->first());
    $this->assertStringContainsString('You have been invited', $notification->toMail($individualUser)->render());
    $this->assertStringContainsString('You have been invited', $notification->toVonage($individualUser)->content);

    expect($individualUser->notifications)->toHaveCount(1);

    $response = $this->actingAs($individualUser)->get(localized_route('dashboard'));
    $response->assertOk();
    $response->assertSee('Accept');
    $response->assertSee(URL::signedRoute('contractor-invitations.accept', $engagement->invitations->first()));
});

test('registered organization can be invited to be an engagement’s community connector', function () {
    $engagement = Engagement::factory()->create(['recruitment' => 'connector']);

    $regulatedOrganization = $engagement->project->projectable;

    $user = User::factory()->create(['context' => 'regulated-organization']);

    $regulatedOrganization->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $organization = Organization::factory()->create(['roles' => ['consultant'], 'published_at' => now()]);

    $organizationUser = User::factory()->create(['context' => 'organization']);

    $organization->users()->attach(
        $organizationUser,
        ['role' => 'admin']
    );

    $response = $this->actingAs($user)->get(localized_route('engagements.add-connector', $engagement));
    $response->assertOk();

    $this->actingAs($user);

    $this->livewire(AddEngagementConnector::class, [
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

    $this->livewire(AddEngagementConnector::class, [
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

    $this->livewire(AddEngagementConnector::class, [
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

    $response = $this->actingAs($user)->get(localized_route('engagements.manage-connector', $engagement));
    $response->assertOk();
    $response->assertSee($organization->name);

    expect($organization->notifications)->toHaveCount(1);

    $response = $this->actingAs($organizationUser)->get(localized_route('dashboard'));
    $response->assertOk();
    $response->assertSee('Accept');
    $response->assertSee(URL::signedRoute('contractor-invitations.accept', $engagement->invitations->first()));
});
