<?php

use App\Models\AccessSupport;
use App\Models\Engagement;
use App\Models\Individual;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\PaymentType;
use App\Models\User;
use App\Notifications\AccessNeedsFacilitationRequested;
use App\Notifications\IndividualContractorInvited;
use App\Notifications\OrganizationAddedToEngagement;
use App\Notifications\OrganizationRemovedFromEngagement;
use App\Notifications\ParticipantAccepted;
use App\Notifications\ParticipantDeclined;
use App\Notifications\ParticipantInvited;
use App\Notifications\ParticipantJoined;
use App\Notifications\ParticipantLeft;
use Database\Seeders\IdentitySeeder;
use Database\Seeders\PaymentTypeSeeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(IdentitySeeder::class);
    seed(PaymentTypeSeeder::class);

    $this->engagement = Engagement::factory()->create(['recruitment' => 'connector', 'signup_by_date' => Carbon::now()->add(1, 'month')->format('Y-m-d')]);
    $this->project = $this->engagement->project;
    $this->project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $this->regulatedOrganization = $this->project->projectable;
    $this->regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $this->regulatedOrganization->users()->attach(
        $this->regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $this->connectorUser = User::factory()->create();
    $this->connectorUser->individual->update(['roles' => ['connector'], 'region' => 'NS', 'locality' => 'Bridgewater']);
    $this->connectorUser->individual->publish();
    $this->individualConnector = $this->connectorUser->individual->fresh();

    $this->connectorOrganization = Organization::factory()->create(['roles' => ['connector'], 'published_at' => now(), 'region' => 'AB', 'locality' => 'Medicine Hat']);
    $this->connectorOrganizationUser = User::factory()->create(['context' => 'organization']);
    $this->connectorOrganization->users()->attach(
        $this->connectorOrganizationUser,
        ['role' => 'admin']
    );

    $this->participantUser = User::factory()->create();
    $this->participantUser->individual->update(['roles' => ['participant'], 'region' => 'NS', 'locality' => 'Bridgewater']);
    $this->participantUser->individual->paymentTypes()->attach(PaymentType::first());
    $this->participant = $this->participantUser->individual->refresh();

    $this->participantOrganization = Organization::factory()->create(['roles' => ['participant'], 'published_at' => now(), 'region' => 'AB', 'locality' => 'Medicine Hat']);
    $this->participantOrganizationUser = User::factory()->create(['context' => 'organization']);
    $this->participantOrganization->users()->attach(
        $this->participantOrganizationUser,
        ['role' => 'admin']
    );
});

test('individual user can accept invitation to an engagement as a connector', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'connector',
        'type' => 'individual',
        'email' => $this->individualConnector->user->email,
    ]);

    actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.manage', $this->engagement))
        ->assertOk()
        ->assertSee($this->individualConnector->name);

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);
    $this->individualConnector->user->notify(new IndividualContractorInvited($invitation));

    $databaseNotification = $this->individualConnector->user->notifications->first();

    actingAs(User::factory()->create())->get($acceptUrl)
        ->assertForbidden();

    actingAs($this->individualConnector->user)->get($acceptUrl)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    $this->engagement = $this->engagement->fresh();

    expect($this->engagement->connector->id)->toEqual($this->individualConnector->id);
    assertModelMissing($databaseNotification);

    expect($this->individualConnector->connectingEngagements->pluck('id'))->toContain($this->engagement->id);
    expect($this->individualConnector->connectingEngagementProjects->pluck('id'))->toContain($this->project->id);
});

test('individual user can decline invitation to an engagement as a connector', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'connector',
        'type' => 'individual',
        'email' => $this->individualConnector->user->email,
    ]);

    $this->individualConnector->user->notify(new IndividualContractorInvited($invitation));
    $databaseNotification = $this->individualConnector->user->notifications->first();

    actingAs(User::factory()->create())->delete(route('contractor-invitations.decline', $invitation))
        ->assertForbidden();

    actingAs($this->individualConnector->user)->delete(route('contractor-invitations.decline', $invitation))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    assertModelMissing($invitation);
    assertModelMissing($databaseNotification);
});

test('organization user can accept invitation to an engagement as a connector', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'connector',
        'type' => 'organization',
        'email' => $this->connectorOrganization->contact_person_email,
    ]);

    actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.manage', $this->engagement))
        ->assertOk()
        ->assertSee($this->connectorOrganization->name);

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);

    actingAs(User::factory()->create())->get($acceptUrl)
        ->assertForbidden();

    actingAs($this->connectorOrganizationUser)->get($acceptUrl)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    $this->engagement = $this->engagement->fresh();

    expect($this->engagement->organizationalConnector->id)->toEqual($this->connectorOrganization->id);

    expect($this->connectorOrganization->connectingEngagements->pluck('id'))->toContain($this->engagement->id);
    expect($this->connectorOrganization->connectingEngagementProjects->pluck('id'))->toContain($this->project->id);
});

test('organization user can decline invitation to an engagement as a connector', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'connector',
        'type' => 'organization',
        'email' => $this->connectorOrganization->contact_person_email,
    ]);

    actingAs(User::factory()->create())->delete(route('contractor-invitations.decline', $invitation))
        ->assertForbidden();

    actingAs($this->connectorOrganizationUser)->delete(route('contractor-invitations.decline', $invitation))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    assertModelMissing($invitation);
});

test('external user cannot invite participants', function () {
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertForbidden();

    actingAs($user)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => 'particpant@example.com',
    ])->assertForbidden();
});

test('project administrator cannot invite participants', function () {
    actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertForbidden();

    actingAs($this->regulatedOrganizationUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => 'participant@example.com',
    ])->assertForbidden();
});

test('participants cannot be invited if participant list is full', function () {
    $this->engagement->participants()->save($this->participant, ['status' => 'confirmed']);
    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id, 'ideal_participants' => 1]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertForbidden();
});

test('external user can be invited as participant', function () {
    Notification::fake();

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => 'external@example.com',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));
});

test('user cannot be invited if they do not have the individual context', function () {
    $user = User::factory()->create(['context' => 'organization']);

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $user->email,
    ])
        ->assertSessionHasErrors('email')
        ->assertRedirect(localized_route('engagements.add-participant', $this->engagement));

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->invitations)->toHaveCount(0);
});

test('individual user cannot be invited if they do not have the participant role', function () {
    $individualUser = User::factory()->create();

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $individualUser->email,
    ])
        ->assertSessionHasErrors('email')
        ->assertRedirect(localized_route('engagements.add-participant', $this->engagement));

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->invitations)->toHaveCount(0);
});

test('individual user cannot be invited if they have an outstanding invitation', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'participant',
        'type' => 'individual',
        'email' => $this->participantUser->email,
    ]);

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ])
        ->assertSessionHasErrors('email')
        ->assertRedirect(localized_route('engagements.add-participant', $this->engagement));

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->invitations)->toHaveCount(1);
});

test('individual user cannot be invited if they are already a participant', function () {
    $this->engagement->participants()->save($this->participant, ['status' => 'confirmed']);
    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ])
        ->assertSessionHasErrors('email')
        ->assertRedirect(localized_route('engagements.add-participant', $this->engagement));

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->invitations)->toHaveCount(0);
});

test('individual participant can accept invitation from individual connector', function () {
    Notification::fake();

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));

    Notification::assertSentTo(
        $this->participantUser, function (ParticipantInvited $notification, $channels) {
            $this->assertStringContainsString('You have been invited', $notification->toMail($this->participantUser)->render());
            $this->assertStringContainsString('You have been invited', $notification->toVonage($this->participantUser)->content);
            expect($notification->toArray($this->participantUser)['invitation_id'])->toEqual($notification->invitation->id);

            return $notification->invitationable->id === $this->engagement->id;
        });

    $invitation = $this->engagement->invitations->where('email', $this->participantUser->email)->first();

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);

    actingAs($this->participantUser)->get($acceptUrl)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    Notification::assertSentTo(
        $this->project, function (ParticipantAccepted $notification, $channels) {
            $this->assertStringContainsString('1 new person accepted their invitation', $notification->toMail($this->project)->render());
            $this->assertStringContainsString('1 new person accepted their invitation', $notification->toVonage($this->project)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    Notification::assertSentTo(
        $this->connectorUser, function (ParticipantAccepted $notification, $channels) {
            $this->assertStringContainsString('1 new person accepted your invitation', $notification->toMail($this->connectorUser)->render());
            $this->assertStringContainsString('1 new person accepted your invitation', $notification->toVonage($this->connectorUser)->content);
            expect($notification->toArray($this->connectorUser)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    assertModelMissing($invitation);

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->participants)->toHaveCount(1);
    expect($this->engagement->participants->first()->id)->toEqual($this->participant->id);
    expect($this->participant->participatingProjects->pluck('id'))->toContain($this->project->id);
});

test('individual participant can access invitation via notifications', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'participant',
        'type' => 'individual',
        'email' => $this->participantUser->email,
    ]);

    $this->participantUser->notify(new ParticipantInvited($invitation));

    actingAs($this->participantUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('You have been invited');
});

test('individual participant can decline invitation from individual connector', function () {
    Notification::fake();

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));

    Notification::assertSentTo(
        $this->participantUser, function (ParticipantInvited $notification, $channels) {
            $this->assertStringContainsString('You have been invited', $notification->toMail($this->participantUser)->render());
            $this->assertStringContainsString('You have been invited', $notification->toVonage($this->participantUser)->content);
            expect($notification->toArray($this->participantUser)['invitation_id'])->toEqual($notification->invitation->id);

            return $notification->invitationable->id === $this->engagement->id;
        });

    $invitation = $this->engagement->invitations->where('email', $this->participantUser->email)->first();

    actingAs($this->participantUser)->delete(route('contractor-invitations.decline', $invitation))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    assertModelMissing($invitation);

    Notification::assertSentTo(
        $this->project, function (ParticipantDeclined $notification, $channels) {
            $this->assertStringContainsString('1 person declined their invitation', $notification->toMail($this->project)->render());
            $this->assertStringContainsString('1 person declined their invitation', $notification->toVonage($this->project)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    Notification::assertSentTo(
        $this->connectorUser, function (ParticipantDeclined $notification, $channels) {
            $this->assertStringContainsString('1 person declined your invitation', $notification->toMail($this->connectorUser)->render());
            $this->assertStringContainsString('1 person declined your invitation', $notification->toVonage($this->connectorUser)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });
});

test('individual participant can accept invitation from organizational connector', function () {
    Notification::fake();

    $this->engagement->update(['organizational_connector_id' => $this->connectorOrganization->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorOrganizationUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorOrganizationUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));

    Notification::assertSentTo(
        $this->participantUser, function (ParticipantInvited $notification, $channels) {
            $this->assertStringContainsString('You have been invited', $notification->toMail($this->participantUser)->render());
            $this->assertStringContainsString('You have been invited', $notification->toVonage($this->participantUser)->content);
            expect($notification->toArray($this->participantUser)['invitation_id'])->toEqual($notification->invitation->id);

            return $notification->invitationable->id === $this->engagement->id;
        });

    $invitation = $this->engagement->invitations->where('email', $this->participantUser->email)->first();

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);

    actingAs($this->participantUser)->get($acceptUrl)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    Notification::assertSentTo(
        $this->project, function (ParticipantAccepted $notification, $channels) {
            $this->assertStringContainsString('1 new person accepted their invitation', $notification->toMail($this->project)->render());
            $this->assertStringContainsString('1 new person accepted their invitation', $notification->toVonage($this->project)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    Notification::assertSentTo(
        $this->connectorOrganization, function (ParticipantAccepted $notification, $channels) {
            $this->assertStringContainsString('1 new person accepted your invitation', $notification->toMail($this->connectorOrganization)->render());
            $this->assertStringContainsString('1 new person accepted your invitation', $notification->toVonage($this->connectorOrganization)->content);
            expect($notification->toArray($this->connectorOrganization)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    assertModelMissing($invitation);

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->participants)->toHaveCount(1);
    expect($this->engagement->participants->first()->id)->toEqual($this->participant->id);
});

test('individual participant can decline invitation from organizational connector', function () {
    Notification::fake();

    $this->engagement->update(['organizational_connector_id' => $this->connectorOrganization->id]);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorOrganizationUser)->get(localized_route('engagements.add-participant', $this->engagement))
        ->assertOk();

    actingAs($this->connectorOrganizationUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));

    Notification::assertSentTo(
        $this->participantUser, function (ParticipantInvited $notification, $channels) {
            $this->assertStringContainsString('You have been invited', $notification->toMail($this->participantUser)->render());
            $this->assertStringContainsString('You have been invited', $notification->toVonage($this->participantUser)->content);
            expect($notification->toArray($this->participantUser)['invitation_id'])->toEqual($notification->invitation->id);

            return $notification->invitationable->id === $this->engagement->id;
        });

    $invitation = $this->engagement->invitations->where('email', $this->participantUser->email)->first();

    actingAs($this->participantUser)->delete(route('contractor-invitations.decline', $invitation))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    assertModelMissing($invitation);

    Notification::assertSentTo(
        $this->project, function (ParticipantDeclined $notification, $channels) {
            $this->assertStringContainsString('1 person declined their invitation', $notification->toMail($this->project)->render());
            $this->assertStringContainsString('1 person declined their invitation', $notification->toVonage($this->project)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    Notification::assertSentTo(
        $this->connectorOrganization, function (ParticipantDeclined $notification, $channels) {
            $this->assertStringContainsString('1 person declined your invitation', $notification->toMail($this->connectorOrganization)->render());
            $this->assertStringContainsString('1 person declined your invitation', $notification->toVonage($this->connectorOrganization)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });
});

test('regulated organization users and community connectors can access accepted invitation notifications', function () {
    $this->connectorUser->notify(new ParticipantAccepted($this->engagement));
    $this->connectorOrganization->notify(new ParticipantAccepted($this->engagement));
    $this->project->notify(new ParticipantAccepted($this->engagement));

    actingAs($this->connectorUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('1 new person accepted your invitation');

    actingAs($this->connectorOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('1 new person accepted your invitation');

    actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('1 new person accepted their invitation');
});

test('regulated organization users and community connectors can access declined invitation notification', function () {
    $this->connectorUser->notify(new ParticipantDeclined($this->engagement));
    $this->connectorOrganization->notify(new ParticipantDeclined($this->engagement));
    $this->project->notify(new ParticipantDeclined($this->engagement));

    actingAs($this->connectorUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('1 person declined your invitation');

    actingAs($this->connectorOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('1 person declined your invitation');

    actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('1 person declined their invitation');
});

test('individual without participant role cannot sign up to an engagement', function () {
    $this->engagement->update(['recruitment' => 'open-call']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->connectorUser)->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertForbidden();

    actingAs($this->connectorUser)->from(localized_route('engagements.sign-up', $this->engagement))->post(localized_route('engagements.join', $this->engagement))
        ->assertForbidden();
});

test('individual participant cannot sign up to an engagement unless the recruitment method is open call', function () {
    actingAs($this->participantUser)->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertForbidden();

    actingAs($this->participantUser)->from(localized_route('engagements.sign-up', $this->engagement))->post(localized_route('engagements.join', $this->engagement))
        ->assertForbidden();
});

test('individual participant cannot sign up to an engagement if the signup by date has passed', function () {
    $this->engagement->update(['recruitment' => 'open-call', 'signup_by_date' => '2022-10-01']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->participantUser)->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertForbidden();

    actingAs($this->participantUser)->from(localized_route('engagements.sign-up', $this->engagement))->post(localized_route('engagements.join', $this->engagement))
        ->assertForbidden();
});

test('individual participant cannot sign up to an engagement if participant list is full', function () {
    $existingParticipant = User::factory()->create()->individual;
    $this->engagement->update(['recruitment' => 'open-call', 'ideal_participants' => 1]);
    $this->engagement->participants()->save($existingParticipant, ['status' => 'confirmed']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->participantUser)->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertForbidden();

    actingAs($this->participantUser)->from(localized_route('engagements.sign-up', $this->engagement))->post(localized_route('engagements.join', $this->engagement))
        ->assertForbidden();
});

test('individual participant cannot sign up to a paid engagement if their payment information is not available', function () {
    $noPaymentUser = User::factory()->create();
    $noPaymentUser->individual->update(['roles' => ['participant'], 'region' => 'NS', 'locality' => 'Bridgewater']);

    $this->engagement->update(['recruitment' => 'open-call']);
    $this->engagement->refresh();

    actingAs($noPaymentUser)->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertForbidden();

    actingAs($noPaymentUser)->from(localized_route('engagements.sign-up', $this->engagement))->post(localized_route('engagements.join', $this->engagement))
        ->assertForbidden();
});

test('individual can sign up to open call engagement', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'context' => 'administrator',
    ]);

    $this->engagement->update(['recruitment' => 'open-call']);
    $this->engagement->refresh();

    // access engagement page
    actingAs($this->participantUser)
        ->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertOk();

    // sign up for engagement
    actingAs($this->participantUser)
        ->from(localized_route('engagements.sign-up', $this->engagement))
        ->post(localized_route('engagements.join', $this->engagement))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.confirm-access-needs', $this->engagement));

    Notification::assertSentTo(
        $this->project, function (ParticipantJoined $notification, $channels) {
            $this->assertStringContainsString('1 new person signed up', $notification->toMail($this->project)->render());
            $this->assertStringContainsString('1 new person signed up', $notification->toVonage($this->project)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    $this->engagement->refresh();
    expect($this->engagement->confirmedParticipants->modelKeys())->toContain($this->participant->id);

    // access confirm access needs page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.sign-up', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertOk();

    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertOk();

    // confirm access needs
    actingAs($this->participantUser)
        ->from(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->post(localized_route('engagements.store-access-needs-permissions', $this->engagement), ['share_access_needs' => '0'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    Notification::assertNotSentTo($admin, AccessNeedsFacilitationRequested::class);

    // redirect to engagement page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    // confirm engagement_individual values
    $this->engagement->refresh();
    $engagement_individual = $this->engagement->participants->first()->pivot;
    expect($engagement_individual->status)->toBeTruthy();
    expect($engagement_individual->share_access_needs)->toBeFalsy();
});

test('individual can sign up to a volunteer engagement without their payment information set', function () {
    $noPaymentUser = User::factory()->create();
    $noPaymentUser->individual->update(['roles' => ['participant'], 'region' => 'NS', 'locality' => 'Bridgewater']);

    $this->engagement->update([
        'recruitment' => 'open-call',
        'paid' => false,
    ]);
    $this->engagement->refresh();

    actingAs($noPaymentUser)->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertOk();

    actingAs($noPaymentUser)->from(localized_route('engagements.sign-up', $this->engagement))->post(localized_route('engagements.join', $this->engagement))
        ->assertRedirect(localized_route('engagements.confirm-access-needs', $this->engagement));
});

test('individual can sign up to a paid engagement with other payment information', function () {
    $otherPaymentUser = User::factory()->create();
    $otherPaymentUser->individual->update([
        'roles' => ['participant'],
        'region' => 'NS',
        'locality' => 'Bridgewater',
        'other_payment_type' => 'Money Order',
    ]);

    $this->engagement->update([
        'recruitment' => 'open-call',
    ]);
    $this->engagement->refresh();

    actingAs($otherPaymentUser)->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertOk();

    actingAs($otherPaymentUser)->from(localized_route('engagements.sign-up', $this->engagement))->post(localized_route('engagements.join', $this->engagement))
        ->assertRedirect(localized_route('engagements.confirm-access-needs', $this->engagement));
});

test('individual can edit their access needs when signing up to an open call engagement', function () {
    seed(AccessSupportSeeder::class);
    Notification::fake();

    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'context' => 'administrator',
    ]);

    $this->engagement->update(['recruitment' => 'open-call']);
    $this->engagement->refresh();

    // access engagement page
    actingAs($this->participantUser)
        ->get(localized_route('engagements.sign-up', $this->engagement))
        ->assertOk();

    // sign up for engagement
    actingAs($this->participantUser)
        ->from(localized_route('engagements.sign-up', $this->engagement))
        ->post(localized_route('engagements.join', $this->engagement))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.confirm-access-needs', $this->engagement));

    Notification::assertSentTo(
        $this->project, function (ParticipantJoined $notification, $channels) {
            $this->assertStringContainsString('1 new person signed up', $notification->toMail($this->project)->render());
            $this->assertStringContainsString('1 new person signed up', $notification->toVonage($this->project)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->confirmedParticipants->modelKeys())->toContain($this->participant->id);

    // access confirm access needs page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.sign-up', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertOk()
        ->assertSeeText('No access needs found.')
        ->assertSee('<button class="secondary" name="share_access_needs" value="0">', false)
        ->assertDontSee('href="'.localized_route('engagements.edit-access-needs-permissions', $this->engagement).'"', false);

    // access access needs settings page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->get(localized_route('settings.edit-access-needs', ['engagement' => $this->engagement]))
        ->assertOk()
        ->assertSeeText('Save and back to confirm access needs', false);

    $audioDescriptions = AccessSupport::where('name->en', 'Audio description for visuals')->first();

    // update access needs settings
    actingAs($this->participantUser)
        ->from(localized_route('settings.edit-access-needs'))
        ->put(localized_route('settings.update-access-needs'), ['return_to_engagement' => $this->engagement->id, 'meeting_access_needs' => [$audioDescriptions->id]])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.confirm-access-needs', $this->engagement));

    $this->participantUser->refresh();

    // return to confirm access needs page
    actingAs($this->participantUser)
        ->from(localized_route('settings.edit-access-needs'))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertOk()
        ->assertDontSeeText(__('No access needs found.'))
        ->assertSeeText(__('Audio description for visuals'))
        ->assertSee('<button class="secondary" name="share_access_needs" value="0">', false)
        ->assertDontSee('href="'.localized_route('engagements.edit-access-needs-permissions', $this->engagement).'"', false);

    // confirm access needs
    actingAs($this->participantUser)
        ->from(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->post(localized_route('engagements.store-access-needs-permissions', $this->engagement), ['share_access_needs' => '0'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    Notification::assertNotSentTo($admin, AccessNeedsFacilitationRequested::class);

    // redirect to engagement page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    // confirm engagement_individual values
    $this->engagement->refresh();
    $engagement_individual = $this->engagement->participants->first()->pivot;
    expect($engagement_individual->status)->toBeTruthy();
    expect($engagement_individual->share_access_needs)->toBeFalsy();

    // redirect to engagement page from edit access needs permissions page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->get(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    // confirm engagement_individual values
    $this->engagement->refresh();
    $engagement_individual = $this->engagement->participants->first()->pivot;
    expect($engagement_individual->status)->toBeTruthy();
    expect($engagement_individual->share_access_needs)->toBeFalsy();
});

test('individual can share their non-anonymizable access needs when signing up to an open call engagement', function () {
    seed(AccessSupportSeeder::class);
    Notification::fake();

    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'context' => 'administrator',
    ]);

    $this->engagement->update(['recruitment' => 'open-call']);
    $this->engagement->refresh();

    // sign up for engagement and set access needs
    $this->engagement->participants()->save($this->participantUser->individual, ['status' => 'confirmed']);
    $accessNeed = AccessSupport::firstWhere('anonymizable', false);
    $anonAccessNeed = AccessSupport::firstWhere('anonymizable', true);
    $this->participantUser->individual->accessSupports()->sync([$accessNeed->id, $anonAccessNeed->id]);
    $this->participantUser->refresh();

    // access confirm access needs page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.sign-up', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertOk()
        ->assertDontSeeText(__('No access needs found.'))
        ->assertSeeText($accessNeed->name)
        ->assertSeeText($anonAccessNeed->name)
        ->assertDontSee('<button class="secondary" name="share_access_needs" value="0">', false)
        ->assertSee('href="'.localized_route('engagements.edit-access-needs-permissions', $this->engagement).'"', false);

    // access edit access needs permissions page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->get(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->assertOk()
        ->assertDontSeeText($anonAccessNeed->name)
        ->assertSeeText($accessNeed->name);

    // share access needs
    actingAs($this->participantUser)
        ->from(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->post(localized_route('engagements.store-access-needs-permissions', $this->engagement), ['share_access_needs' => '1'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    expect(flash()->class)->toStartWith('success');
    expect(flash()->message)->toBe(__('Your preference for sharing your access needs has been saved.'));

    Notification::assertNotSentTo($admin, AccessNeedsFacilitationRequested::class);

    // redirect to engagement page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    // confirm engagement_individual values
    $this->engagement->refresh();
    $engagement_individual = $this->engagement->participants->first()->pivot;
    expect($engagement_individual->status)->toBeTruthy();
    expect($engagement_individual->share_access_needs)->toBeTruthy();
});

test('individual can choose not to share their non-anonymizable access needs when signing up to an open call engagement', function () {
    seed(AccessSupportSeeder::class);
    Notification::fake();

    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'context' => 'administrator',
    ]);

    $this->engagement->update(['recruitment' => 'open-call']);
    $this->engagement->refresh();

    // sign up for engagement and set access needs
    $this->engagement->participants()->save($this->participantUser->individual, ['status' => 'confirmed']);
    $accessNeed = AccessSupport::firstWhere('anonymizable', false);
    $anonAccessNeed = AccessSupport::firstWhere('anonymizable', true);
    $this->participantUser->individual->accessSupports()->sync([$accessNeed->id, $anonAccessNeed->id]);
    $this->participantUser->refresh();

    // access confirm access needs page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.sign-up', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertOk()
        ->assertDontSeeText('No access needs found.')
        ->assertSeeText($accessNeed->name)
        ->assertSeeText($anonAccessNeed->name)
        ->assertDontSee('<button class="secondary" name="share_access_needs" value="0">', false)
        ->assertSee('href="'.localized_route('engagements.edit-access-needs-permissions', $this->engagement).'"', false);

    // access edit access needs permissions page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->get(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->assertOk()
        ->assertDontSeeText($anonAccessNeed->name)
        ->assertSeeText($accessNeed->name);

    // don't share access needs
    actingAs($this->participantUser)
        ->from(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->post(localized_route('engagements.store-access-needs-permissions', $this->engagement), ['share_access_needs' => '0'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    expect(flash()->class)->toStartWith('success');
    expect(flash()->message)->toBe(__('Your preference for sharing your access needs has been saved.'));

    Notification::assertSentTo(
        $admin,
        function (AccessNeedsFacilitationRequested $notification, $channels) {
            expect($notification->toMail()->subject)->toBe(__(':name requires access needs facilitation', ['name' => $this->participant->name]));
            $renderedMail = $notification->toMail($this->project)->render();
            $this->assertStringContainsString(__('Please contact :name to facilitate their access needs being met on the engagement', ['name' => $this->participant->name]), $renderedMail);
            expect($notification->toArray()['individual_id'])->toEqual($this->participant->id);
            expect($notification->toArray()['engagement_id'])->toEqual($this->engagement->id);
            expect($notification->engagement->id)->toBe($this->engagement->id);

            return $notification->user->id === $this->participant->user->id;
        }
    );

    // redirect to engagement page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    // confirm engagement_individual values
    $this->engagement->refresh();
    $engagement_individual = $this->engagement->participants->first()->pivot;
    expect($engagement_individual->status)->toBeTruthy();
    expect($engagement_individual->share_access_needs)->toBeFalsy();
});

test('individual can choose not to share their other access needs when signing up to an open call engagement', function () {
    seed(AccessSupportSeeder::class);
    Notification::fake();

    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'context' => 'administrator',
    ]);

    $this->engagement->update(['recruitment' => 'open-call']);
    $this->engagement->refresh();

    // sign up for engagement and set access needs
    $this->engagement->participants()->save($this->participantUser->individual, ['status' => 'confirmed']);
    $otherAccessNeed = 'custom access need';
    $this->participantUser->individual->update(['other_access_need' => $otherAccessNeed]);
    $this->participantUser->individual->save();
    $this->participantUser->refresh();

    // access confirm access needs page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.sign-up', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertOk()
        ->assertDontSeeText('No access needs found.')
        ->assertSeeText($otherAccessNeed)
        ->assertDontSee('<button class="secondary" name="share_access_needs" value="0">', false)
        ->assertSee('href="'.localized_route('engagements.edit-access-needs-permissions', $this->engagement).'"', false);

    // access edit access needs permissions page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->get(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->assertOk()
        ->assertSeeText($otherAccessNeed);

    // don't share access needs
    actingAs($this->participantUser)
        ->from(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->post(localized_route('engagements.store-access-needs-permissions', $this->engagement), ['share_access_needs' => '0'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    expect(flash()->class)->toStartWith('success');
    expect(flash()->message)->toBe(__('Your preference for sharing your access needs has been saved.'));

    Notification::assertSentTo(
        $admin,
        function (AccessNeedsFacilitationRequested $notification, $channels) {
            expect($notification->toMail()->subject)->toBe(__(':name requires access needs facilitation', ['name' => $this->participant->name]));
            $renderedMail = $notification->toMail($this->project)->render();
            $this->assertStringContainsString(__('Please contact :name to facilitate their access needs being met on the engagement', ['name' => $this->participant->name]), $renderedMail);
            expect($notification->toArray()['individual_id'])->toEqual($this->participant->id);
            expect($notification->toArray()['engagement_id'])->toEqual($this->engagement->id);
            expect($notification->engagement->id)->toBe($this->engagement->id);

            return $notification->user->id === $this->participant->user->id;
        }
    );

    // redirect to engagement page
    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.confirm-access-needs', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    actingAs($this->participantUser)
        ->from(localized_route('engagements.show', $this->engagement))
        ->get(localized_route('engagements.edit-access-needs-permissions', $this->engagement))
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    // confirm engagement_individual values
    $this->engagement->refresh();
    $engagement_individual = $this->engagement->participants->first()->pivot;
    expect($engagement_individual->status)->toBeTruthy();
    expect($engagement_individual->share_access_needs)->toBeFalsy();
});

test('regulated users can access notifications of participants signing up for their engagements', function () {
    $this->project->notify(new ParticipantJoined($this->engagement));

    actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('1 new person signed up');
});

test('individual cannot leave an open call engagement if the signup by date has passed', function () {
    $this->engagement->update(['recruitment' => 'open-call', 'signup_by_date' => '2022-10-01']);
    $this->engagement->participants()->save($this->participant, ['status' => 'confirmed']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->participantUser)->get(localized_route('engagements.confirm-leave', $this->engagement))
        ->assertSee('please contact us');

    actingAs($this->participantUser)->post(localized_route('engagements.leave', $this->engagement))
        ->assertForbidden();
});

test('individual can leave an open call engagement', function () {
    Notification::fake();

    $this->engagement->update(['recruitment' => 'open-call']);
    $this->engagement->participants()->save($this->participant, ['status' => 'confirmed']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->participantUser)->get(localized_route('engagements.confirm-leave', $this->engagement))
        ->assertSee('Are you sure you want to leave this engagement?');

    actingAs($this->participantUser)->post(localized_route('engagements.leave', $this->engagement))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.show', $this->engagement));

    Notification::assertSentTo(
        $this->project, function (ParticipantLeft $notification, $channels) {
            $this->assertStringContainsString('1 participant left', $notification->toMail($this->project)->render());
            $this->assertStringContainsString('1 participant left', $notification->toVonage($this->project)->content);
            expect($notification->toArray($this->project)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->confirmedParticipants)->toHaveCount(0);
});

test('regulated users can access notifications of participants leaving their engagements', function () {
    $this->project->notify(new ParticipantLeft($this->engagement));

    actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('1 participant left');
});

test('individual cannot leave an engagement which uses a community connector', function () {
    $this->engagement->participants()->save($this->participant, ['status' => 'confirmed']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->participantUser)->get(localized_route('engagements.confirm-leave', $this->engagement))
        ->assertSee('you will need to contact its Community Connector');

    actingAs($this->participantUser)->post(localized_route('engagements.leave', $this->engagement))
        ->assertForbidden();
});

test('organization cannot be added to individual engagement', function () {
    actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.manage-organization', $this->engagement))
        ->assertForbidden();

    actingAs($this->regulatedOrganizationUser)->post(localized_route('engagements.add-organization', $this->engagement), [
        'organization_id' => $this->participantOrganization->id,
    ])->assertForbidden();
});

test('organization without participant role cannot be added to organizational engagement', function () {
    $this->engagement->update(['who' => 'organization']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->regulatedOrganizationUser)->post(localized_route('engagements.add-organization', $this->engagement), [
        'organization_id' => $this->connectorOrganization->id,
    ])->assertSessionHasErrors('organization_id');
});

test('organization cannot be added to organizational engagement with attached organization', function () {
    $this->engagement->update(['who' => 'organization']);
    $this->engagement->organization()->associate($this->participantOrganization->id);
    $this->engagement->save();
    $this->engagement = $this->engagement->fresh();

    actingAs($this->regulatedOrganizationUser)->post(localized_route('engagements.add-organization', $this->engagement), [
        'organization_id' => $this->participantOrganization->id,
    ])->assertForbidden();
});

test('organization can be added to organizational engagement', function () {
    Notification::fake();

    $this->engagement->update(['who' => 'organization']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.manage-organization', $this->engagement))
        ->assertOk();

    actingAs($this->regulatedOrganizationUser)->post(localized_route('engagements.add-organization', $this->engagement), [
        'organization_id' => $this->participantOrganization->id,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage-organization', $this->engagement));

    Notification::assertSentTo(
        $this->participantOrganization, function (OrganizationAddedToEngagement $notification, $channels) {
            $this->assertStringContainsString('Your organization has been added', $notification->toMail($this->participantOrganization)->render());
            $this->assertStringContainsString('Your organization has been added', $notification->toVonage($this->participantOrganization)->content);
            expect($notification->toArray($this->participantOrganization)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->organization->id)->toEqual($this->participantOrganization->id);
});

test('organization can access notification of being added to organizational engagement', function () {
    $this->engagement->update(['who' => 'organization']);
    $this->engagement = $this->engagement->fresh();

    $this->participantOrganization->notify(new OrganizationAddedToEngagement($this->engagement));

    actingAs($this->participantOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your organization has been added');
});

test('organization cannot be removed from organizational engagement without attached organization', function () {
    $this->engagement->update(['who' => 'organization']);
    $this->engagement = $this->engagement->fresh();

    actingAs($this->regulatedOrganizationUser)->post(localized_route('engagements.remove-organization', $this->engagement))
        ->assertForbidden();
});

test('organization can be removed from organizational engagement', function () {
    Notification::fake();

    $this->engagement->update(['who' => 'organization']);
    $this->engagement->organization()->associate($this->participantOrganization->id);
    $this->engagement->save();
    $this->engagement = $this->engagement->fresh();

    actingAs($this->regulatedOrganizationUser)->post(localized_route('engagements.remove-organization', $this->engagement))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('engagements.manage-organization', $this->engagement));

    Notification::assertSentTo(
        $this->participantOrganization, function (OrganizationRemovedFromEngagement $notification, $channels) {
            $this->assertStringContainsString('Your organization has been removed', $notification->toMail($this->participantOrganization)->render());
            $this->assertStringContainsString('Your organization has been removed', $notification->toVonage($this->participantOrganization)->content);
            expect($notification->toArray($this->participantOrganization)['engagement_id'])->toEqual($notification->engagement->id);

            return $notification->engagement->id === $this->engagement->id;
        });

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->organization)->toBeNull();
});

test('organization can access notification of being removed from organizational engagement', function () {
    $this->engagement->update(['who' => 'organization']);
    $this->engagement = $this->engagement->fresh();

    $this->participantOrganization->notify(new OrganizationRemovedFromEngagement($this->engagement));

    actingAs($this->participantOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your organization has been removed from');
});
