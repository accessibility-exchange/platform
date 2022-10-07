<?php

use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\IndividualContractorInvited;
use App\Notifications\ParticipantAccepted;
use App\Notifications\ParticipantDeclined;
use App\Notifications\ParticipantInvited;
use Database\Seeders\DisabilityTypeSeeder;

beforeEach(function () {
    $this->seed(DisabilityTypeSeeder::class);

    $this->engagement = Engagement::factory()->create(['recruitment' => 'connector']);
    $this->project = $this->engagement->project;
    $this->project->update(['estimate_requested_at' => now(), 'agreement_received_at' => now()]);
    $this->regulatedOrganization = $this->project->projectable;
    $this->regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $this->regulatedOrganization->users()->attach(
        $this->regulatedOrganizationUser,
        ['role' => 'admin']
    );

    $this->connectorUser = User::factory()->create();
    $this->connectorUser->individual->update(['roles' => ['connector']]);
    $this->connectorUser->individual->publish();
    $this->individualConnector = $this->connectorUser->individual->fresh();

    $this->connectorOrganization = Organization::factory()->create(['roles' => ['connector'], 'published_at' => now()]);
    $this->connectorOrganizationUser = User::factory()->create(['context' => 'organization']);
    $this->connectorOrganization->users()->attach(
        $this->connectorOrganizationUser,
        ['role' => 'admin']
    );

    $this->participantUser = User::factory()->create();
    $this->participantUser->individual->update(['roles' => ['participant']]);
    $this->participant = $this->participantUser->individual->fresh();
});

test('individual user can accept invitation to an engagement as a connector', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'connector',
        'type' => 'individual',
        'email' => $this->individualConnector->user->email,
    ]);

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.manage', $this->engagement));
    $response->assertOk();
    $response->assertSee($this->individualConnector->name);

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);
    $this->individualConnector->user->notify(new IndividualContractorInvited($invitation));

    $databaseNotification = $this->individualConnector->user->notifications->first();

    $response = $this->actingAs(User::factory()->create())->get($acceptUrl);
    $response->assertForbidden();

    $response = $this->actingAs($this->individualConnector->user)->get($acceptUrl);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $this->engagement = $this->engagement->fresh();

    expect($this->engagement->connector->id)->toEqual($this->individualConnector->id);
    $this->assertModelMissing($databaseNotification);
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

    $response = $this->actingAs(User::factory()->create())->delete(route('contractor-invitations.decline', $invitation));
    $response->assertForbidden();

    $response = $this->actingAs($this->individualConnector->user)->delete(route('contractor-invitations.decline', $invitation));
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $this->assertModelMissing($invitation);
    $this->assertModelMissing($databaseNotification);
});

test('organization user can accept invitation to an engagement as a connector', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'connector',
        'type' => 'organization',
        'email' => $this->connectorOrganization->contact_person_email,
    ]);

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.manage', $this->engagement));
    $response->assertOk();
    $response->assertSee($this->connectorOrganization->name);

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);

    $response = $this->actingAs(User::factory()->create())->get($acceptUrl);
    $response->assertForbidden();

    $response = $this->actingAs($this->connectorOrganizationUser)->get($acceptUrl);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $this->engagement = $this->engagement->fresh();

    expect($this->engagement->organizationalConnector->id)->toEqual($this->connectorOrganization->id);
});

test('organization user can decline invitation to an engagement as a connector', function () {
    $invitation = Invitation::factory()->create([
        'invitationable_type' => 'App\Models\Engagement',
        'invitationable_id' => $this->engagement->id,
        'role' => 'connector',
        'type' => 'organization',
        'email' => $this->connectorOrganization->contact_person_email,
    ]);

    $response = $this->actingAs(User::factory()->create())->delete(route('contractor-invitations.decline', $invitation));
    $response->assertForbidden();

    $response = $this->actingAs($this->connectorOrganizationUser)->delete(route('contractor-invitations.decline', $invitation));
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

    $this->assertModelMissing($invitation);
});

test('external user cannot invite participants', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertForbidden();

    $response = $this->actingAs($user)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => 'particpant@example.com',
    ]);
    $response->assertForbidden();
});

test('project administrator cannot invite participants', function () {
    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertForbidden();

    $response = $this->actingAs($this->regulatedOrganizationUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => 'participant@example.com',
    ]);
    $response->assertForbidden();
});

test('external user can be invited as participant', function () {
    Notification::fake();

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    $response = $this->actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => 'external@example.com',
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));
});

test('user cannot be invited if they do not have the individual context', function () {
    $user = User::factory()->create(['context' => 'organization']);

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    $response = $this->actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $user->email,
    ]);
    $response->assertSessionHasErrors('email');
    $response->assertRedirect(localized_route('engagements.add-participant', $this->engagement));

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->invitations)->toHaveCount(0);
});

test('individual user cannot be invited if they do not have the participant role', function () {
    $individualUser = User::factory()->create();

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    $response = $this->actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $individualUser->email,
    ]);
    $response->assertSessionHasErrors('email');
    $response->assertRedirect(localized_route('engagements.add-participant', $this->engagement));

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

    $response = $this->actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ]);
    $response->assertSessionHasErrors('email');
    $response->assertRedirect(localized_route('engagements.add-participant', $this->engagement));

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->invitations)->toHaveCount(1);
});

test('individual user cannot be invited if they are already a participant', function () {
    $this->engagement->participants()->save($this->participant);
    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    $response = $this->actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ]);
    $response->assertSessionHasErrors('email');
    $response->assertRedirect(localized_route('engagements.add-participant', $this->engagement));

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->invitations)->toHaveCount(0);
});

test('individual participant can accept invitation from individual connector', function () {
    Notification::fake();

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    $response = $this->actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));
    Notification::assertSentTo(
        $this->participantUser, function (ParticipantInvited $notification, $channels) {
            $this->assertStringContainsString('You have been invited', $notification->toMail($this->participantUser)->render());
            $this->assertStringContainsString('You have been invited', $notification->toVonage($this->participantUser)->content);
            expect($notification->toArray($this->participantUser)['invitation_id'])->toEqual($notification->invitation->id);

            return $notification->invitationable->id === $this->engagement->id;
        });

    $invitation = $this->engagement->invitations->where('email', $this->participantUser->email)->first();

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);

    $response = $this->actingAs($this->participantUser)->get($acceptUrl);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

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

    $this->assertModelMissing($invitation);

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->participants)->toHaveCount(1);
    expect($this->engagement->participants->first()->id)->toEqual($this->participant->id);
});

test('individual participant can decline invitation from individual connector', function () {
    Notification::fake();

    $this->engagement->update(['individual_connector_id' => $this->individualConnector->id]);
    $this->engagement = $this->engagement->fresh();

    $response = $this->actingAs($this->connectorUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));
    Notification::assertSentTo(
        $this->participantUser, function (ParticipantInvited $notification, $channels) {
            $this->assertStringContainsString('You have been invited', $notification->toMail($this->participantUser)->render());
            $this->assertStringContainsString('You have been invited', $notification->toVonage($this->participantUser)->content);
            expect($notification->toArray($this->participantUser)['invitation_id'])->toEqual($notification->invitation->id);

            return $notification->invitationable->id === $this->engagement->id;
        });

    $invitation = $this->engagement->invitations->where('email', $this->participantUser->email)->first();

    $response = $this->actingAs($this->participantUser)->delete(route('contractor-invitations.decline', $invitation));
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));
    $this->assertModelMissing($invitation);

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

    $response = $this->actingAs($this->connectorOrganizationUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorOrganizationUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));
    Notification::assertSentTo(
        $this->participantUser, function (ParticipantInvited $notification, $channels) {
            $this->assertStringContainsString('You have been invited', $notification->toMail($this->participantUser)->render());
            $this->assertStringContainsString('You have been invited', $notification->toVonage($this->participantUser)->content);
            expect($notification->toArray($this->participantUser)['invitation_id'])->toEqual($notification->invitation->id);

            return $notification->invitationable->id === $this->engagement->id;
        });

    $invitation = $this->engagement->invitations->where('email', $this->participantUser->email)->first();

    $acceptUrl = URL::signedRoute('contractor-invitations.accept', ['invitation' => $invitation]);

    $response = $this->actingAs($this->participantUser)->get($acceptUrl);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));

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

    $this->assertModelMissing($invitation);

    $this->engagement = $this->engagement->fresh();
    expect($this->engagement->participants)->toHaveCount(1);
    expect($this->engagement->participants->first()->id)->toEqual($this->participant->id);
});

test('individual participant can decline invitation from organizational connector', function () {
    Notification::fake();

    $this->engagement->update(['organizational_connector_id' => $this->connectorOrganization->id]);
    $this->engagement = $this->engagement->fresh();

    $response = $this->actingAs($this->connectorOrganizationUser)->get(localized_route('engagements.add-participant', $this->engagement));
    $response->assertOk();

    $response = $this->actingAs($this->connectorOrganizationUser)->post(localized_route('engagements.invite-participant', $this->engagement), [
        'email' => $this->participantUser->email,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('engagements.manage-participants', $this->engagement));
    Notification::assertSentTo(
        $this->participantUser, function (ParticipantInvited $notification, $channels) {
            $this->assertStringContainsString('You have been invited', $notification->toMail($this->participantUser)->render());
            $this->assertStringContainsString('You have been invited', $notification->toVonage($this->participantUser)->content);
            expect($notification->toArray($this->participantUser)['invitation_id'])->toEqual($notification->invitation->id);

            return $notification->invitationable->id === $this->engagement->id;
        });

    $invitation = $this->engagement->invitations->where('email', $this->participantUser->email)->first();

    $response = $this->actingAs($this->participantUser)->delete(route('contractor-invitations.decline', $invitation));
    $response->assertSessionHasNoErrors();
    $response->assertRedirect(localized_route('dashboard'));
    $this->assertModelMissing($invitation);

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
