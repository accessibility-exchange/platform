<?php

use App\Enums\ConsultingService;
use App\Enums\ContactMethod;
use App\Enums\IdentityCluster;
use App\Enums\OrganizationRole;
use App\Enums\ProvinceOrTerritory;
use App\Enums\StaffHaveLivedExperience;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Mail\Invitation as InvitationMessage;
use App\Models\Identity;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\NewMemberJoined;
use Database\Seeders\IdentitySeeder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

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
    $regulatedOrganization = RegulatedOrganization::factory()->create([
        'contact_person_email' => $user->email,
    ]);
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => $user->email,
    ]);

    $acceptUrl = URL::signedRoute('invitations.accept', ['invitation' => $invitation]);

    actingAs($user)->get($acceptUrl);

    Notification::assertSentTo(
        $admin,
        function (NewMemberJoined $notification, array $channels) {
            expect($channels)->toContain('mail', 'database');

            return true;
        }
    );
});

test('new member joined regulated organization notification has correct content', function () {

    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regOrganization = RegulatedOrganization::factory()->create([
        'name' => ['en' => 'Test Regulated Org', 'fr' => 'Org Test Regulated'],
        'contact_person_email' => $user->email,
    ]);

    $notification = new NewMemberJoined(
        memberName: 'Test User',
        account: $regOrganization,
        teamRole: TeamRole::Member,
    );

    $mail = $notification->toMail();
    expect($mail->subject)->toBe(__('New member joined'));
    expect($mail->introLines)->toContain(__('A new member has joined a regulated organization on The Accessibility Exchange.'));

    $array = $notification->toArray();
    expect($array['member_name'])->toBe('Test User');
    expect($array['account_id'])->toBe($regOrganization->id);
    expect($array['account_type'])->toBe(get_class($regOrganization));
    expect($array['team_role'])->toBe('member');
});

test('platform admins can view the new member joined regulated organization notification', function () {

    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);
    $user = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $regOrganization = RegulatedOrganization::factory()->create([
        'name' => ['en' => 'Test Regulated Org'],
        'contact_person_email' => $user->email,
    ]);

    $admin->notify(new NewMemberJoined(
        memberName: 'Test User',
        account: $regOrganization,
        teamRole: TeamRole::Member,
    ));

    actingAs($admin)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('New member joined')
        ->assertSee('Test User')
        ->assertSee('Test Regulated Org')
        ->assertSee('Regulated organization')
        ->assertDontSee(localized_route('regulated-organizations.show', $regOrganization));
});

test('platform admins can click through to the organization from new member joined notification', function () {
    seed(IdentitySeeder::class);

    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);
    $user = User::factory()->create(['context' => UserContext::Organization->value]);

    $organization = Organization::factory()->create([
        'name' => ['en' => 'Test Org'],
        'contact_person_name' => 'Contact Name',
        'contact_person_phone' => '4165555555',
        'contact_person_email' => $user->email,
        'locality' => 'Toronto',
        'preferred_contact_method' => ContactMethod::Email->value,
        'region' => ProvinceOrTerritory::Ontario->value,
        'roles' => [OrganizationRole::AccessibilityConsultant->value],
        'consulting_services' => [ConsultingService::Analysis->value],
        'staff_lived_experience' => StaffHaveLivedExperience::Yes->value,
    ]);

    $organization->constituentIdentities()->attach(
        Identity::whereJsonContains('clusters', IdentityCluster::Area)->first()->id
    );

    expect($organization->isPublishable())->toBeTrue();

    $admin->notify(new NewMemberJoined(
        memberName: 'Test User',
        account: $organization,
        teamRole: TeamRole::Member,
    ));

    actingAs($admin)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('New member joined')
        ->assertSee('Test User')
        ->assertSee('Test Org')
        ->assertSee('Organization')
        ->assertSee(localized_route('organizations.show', $organization));
});
