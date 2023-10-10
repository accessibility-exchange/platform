<?php

use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Mail\Invitation as InvitationMessage;
use App\Models\Invitation;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use function Pest\Laravel\{actingAs};

test('create invitation', function () {
    Mail::fake();

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
        ->create();

    $response = $this->actingAs($user)->post(localized_route('invitations.create'), [
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => 'newuser@here.com',
        'role' => TeamRole::Member->value,
    ]);

    $response->assertSessionHasNoErrors();
    expect(flash()->class)->toBe('success');
    expect(flash()->message)->toBe(__('invitation.create_invitation_succeeded'));

    Mail::assertSent(InvitationMessage::class, function (InvitationMessage $mail) {
        return $mail->hasTo('newuser@here.com');
    });

    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});

test('create invitation validation errors', function ($data, array $errors) {
    Mail::fake();

    $user = User::factory()->create([
        'context' => UserContext::RegulatedOrganization->value,
        'email' => 'invitation.existing.member.test@example.com',
    ]);

    $regulatedOrganization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => TeamRole::Administrator->value])
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

    $response = $this->actingAs($user)->get($acceptUrl);
    $response->assertSessionHasNoErrors();
    expect(flash()->class)->toBe('success');
    expect(flash()->message)->toBe(__('invitation.accept_invitation_succeeded', ['invitationable' => $regulatedOrganization->name]));

    $this->assertTrue($regulatedOrganization->fresh()->hasUserWithEmail($user->email));
    $response->assertRedirect(localized_route('dashboard'));
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

    $response = $this->actingAs($user)->get($acceptUrl);
    $response->assertSessionHasErrors([
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

    $response = $this->actingAs($user)->get($acceptUrl);
    $response->assertSessionHasErrors([
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

    $response = $this->actingAs($user)->delete(route('invitations.decline', $invitation));
    $response->assertSessionHasNoErrors();
    expect(flash()->class)->toBe('success');
    expect(flash()->message)->toBe(__('invitation.decline_invitation_succeeded', ['invitationable' => $regulatedOrganization->name]));

    expect(Invitation::find($invitation))->toHaveCount(0);

    $response->assertRedirect(localized_route('dashboard'));
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

    $response = $this->actingAs($user)->delete(route('invitations.destroy', $invitation));
    $response->assertSessionHasNoErrors();
    expect(flash()->class)->toBe('success');
    expect(flash()->message)->toBe(__('invitation.cancel_invitation_succeeded'));

    expect(Invitation::find($invitation))->toHaveCount(0);

    $response->assertRedirect(localized_route('settings.edit-roles-and-permissions'));
});
