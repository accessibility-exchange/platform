<?php

use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Mail\Invitation as InvitationMessage;
use App\Models\Invitation;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\NewMemberJoinedOrganization;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;

test('create invitation', function () {
    Mail::fake();

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();

    actingAs($user)->post(localized_route('invitations.create'), [
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => 'newuser@here.com',
        'role' => TeamRole::Member->value,
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-roles-and-permissions'));

    expect(flash()->class)->toStartWith('success');
    expect(flash()->message)->toBe(__('invitation.create_invitation_succeeded'));

    Mail::assertSent(InvitationMessage::class, function (InvitationMessage $mail) {
        return $mail->hasTo('newuser@here.com');
    });
});

test('create invitation validation errors', function ($data, array $errors) {
    Mail::fake();

    $user = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
        'email' => 'invitation.existing.member.test@example.com',
    ]);

    $otherUser = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
        'email' => 'invitation.existing.user.existing.membership.test@example.com',
    ]);

    User::factory()->create([
        'context' => UserContext::Organization->value,
        'email' => 'invitation.existing.user.test@example.com',
    ]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();

    RegulatedOrganization::factory()
        ->hasAttached($otherUser, ['role' => TeamRole::Administrator->value])
        ->create();

    Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => 'invitation.sent.test@example.com',
    ]);

    $postData = array_merge([
        'email' => 'invitation.user.test@example.com',
        'role' => TeamRole::Member->value,
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
    ], $data);

    actingAs($user)
        ->post(localized_route('invitations.create'), $postData)
        ->assertSessionHasErrors($errors);

    Mail::assertNothingOutgoing();
})->with('storeInvitationRequestValidationErrors');

test('accept invitation request', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    actingAs($user)->get($acceptUrl)
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect(flash()->class)->toStartWith('success');
    expect(flash()->message)->toBe(__('invitation.accept_invitation_succeeded', ['invitationable' => $regulatedOrganization->name]));
    expect($regulatedOrganization->fresh()->hasUserWithEmail($user->email))->toBeTrue();
});

test('accept invitation request - validation errors: existing member', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Member->value])
        ->create();

    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    actingAs($user)->get($acceptUrl)
        ->assertSessionHasErrors([
            'email' => __('invitation.invited_user_already_belongs_to_this_team'),
        ], errorBag: 'acceptInvitation');
});

test('accept invitation request - validation errors: member of other team', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Member->value])
        ->create();

    $regulatedOrganization = RegulatedOrganization::factory()->create();

    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    actingAs($user)->get($acceptUrl)
        ->assertSessionHasErrors([
            'email' => __('invitation.invited_user_already_belongs_to_a_team'),
        ], errorBag: 'acceptInvitation');
});

test('decline invitation request', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => $user->email,
    ]);

    actingAs($user)->delete(route('invitations.decline', $invitation))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('dashboard'));

    expect(flash()->class)->toStartWith('success');
    expect(flash()->message)->toBe(__('invitation.decline_invitation_succeeded', ['invitationable' => $regulatedOrganization->name]));
    expect(Invitation::find($invitation))->toHaveCount(0);
});

test('destroy invitation', function () {
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();

    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => 'newuser@here.com',
    ]);

    actingAs($user)->delete(route('invitations.destroy', $invitation))
        ->assertSessionHasNoErrors()
        ->assertRedirect(localized_route('settings.edit-roles-and-permissions'));

    expect(flash()->class)->toStartWith('success');
    expect(flash()->message)->toBe(__('invitation.cancel_invitation_succeeded'));
    expect(Invitation::find($invitation))->toHaveCount(0);
});

test('platform admins are notified when a member accepts an organization or regulated organization invitation', function () {
    Notification::fake();

    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    actingAs($user)->get($acceptUrl);

    Notification::assertSentTo(
        $admin,
        function (NewMemberJoinedOrganization $notification, array $channels) {
            expect($channels)->toContain('mail', 'database');

            return true;
        }
    );
});

test('new member joined organization notification has correct content', function () {
    $organization = RegulatedOrganization::factory()->create([
        'name' => ['en' => 'Test Org', 'fr' => 'Org Test'],
    ]);

    $notification = new NewMemberJoinedOrganization(
        memberName: 'Test User',
        organization: $organization,
        memberEmail: 'test@example.com',
        memberRole: 'member',
        userContext: UserContext::RegulatedOrganization->value,
    );

    $mail = $notification->toMail();
    expect($mail->subject)->toBe(__('New member joined organization'));

    $array = $notification->toArray();
    expect($array['title'])->toBe(__('New member joined organization'));
    expect($array['body'])->toContain('Test User');
    expect($array['body'])->toContain('Test Org');
});

test('platform admins can view new member joined organization notification', function () {
    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);
    $organization = RegulatedOrganization::factory()->create([
        'name' => ['en' => 'Test Org'],
    ]);

    $admin->notify(new NewMemberJoinedOrganization(
        memberName: 'Test User',
        organization: $organization,
        memberEmail: 'test@example.com',
        memberRole: 'member',
        userContext: UserContext::RegulatedOrganization->value,
    ));

    actingAs($admin)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('New member joined organization')
        ->assertSee('Test User');
});
