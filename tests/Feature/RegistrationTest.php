<?php

use App\Enums\ConsultingService;
use App\Enums\IndividualRole;
use App\Enums\MeetingType;
use App\Enums\UserContext;
use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\NewUserRegistered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\from;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withSession;

pest()->group('user', 'individual', 'organization', 'regulated-organization');

test('registration screen can be rendered', function () {
    get(localized_route('register'))->assertOk();
});

test('new users can register', function () {
    User::factory()->create(['email' => 'me@here.com']);

    from(localized_route('register', ['step' => 1]))
        ->post(localized_route('register-languages'), [
            'locale' => 'en',
        ])
        ->assertRedirect(localized_route('register', ['step' => 2]))
        ->assertSessionHas('locale', 'en');

    from(localized_route('register', ['step' => 2]))
        ->withSession([
            'locale' => 'en',
        ])
        ->post(localized_route('register-context'), [
            'context' => UserContext::Individual->value,
        ])
        ->assertRedirect(localized_route('register', ['step' => 3]))
        ->assertSessionHas('context', UserContext::Individual->value)
        ->assertSessionHas('isNewOrganizationContext', false);

    from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'context' => UserContext::Individual->value,
        ])
        ->post(localized_route('register-details'), [
            'name' => 'Test User',
            'email' => 'me@here.com',
        ])
        ->assertSessionHasErrors(['email'])
        ->assertRedirect(localized_route('register', ['step' => 3]));

    from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'context' => UserContext::Individual->value,
        ])
        ->post(localized_route('register-details'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])
        ->assertRedirect(localized_route('register', ['step' => 4]))
        ->assertSessionHas('name', 'Test User')
        ->assertSessionHas('email', 'test@example.com');

    withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => UserContext::Individual->value,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ])->assertRedirect(localized_route('users.show-introduction'));

    assertAuthenticated();
});

test('new users can register - organization', function () {
    User::factory()->create(['email' => 'me@here.com']);

    from(localized_route('register', ['step' => 1]))
        ->post(localized_route('register-languages'), [
            'locale' => 'en',
        ])
        ->assertRedirect(localized_route('register', ['step' => 2]))
        ->assertSessionHas('locale', 'en');

    from(localized_route('register', ['step' => 2]))
        ->withSession([
            'locale' => 'en',
        ])
        ->post(localized_route('register-context'), [
            'context' => UserContext::Organization->value,
        ])
        ->assertRedirect(localized_route('register', ['step' => 3]))
        ->assertSessionHas('context', UserContext::Organization->value)
        ->assertSessionHas('isNewOrganizationContext', true);

    from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'context' => UserContext::Organization->value,
        ])
        ->post(localized_route('register-details'), [
            'name' => 'Test User',
            'email' => 'me@here.com',
        ])
        ->assertSessionHasErrors(['email'])
        ->assertRedirect(localized_route('register', ['step' => 3]));

    from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'context' => UserContext::Organization->value,
        ])
        ->post(localized_route('register-details'), [
            'name' => 'Test User',
            'email' => 'test-org@example.com',
        ])
        ->assertRedirect(localized_route('register', ['step' => 4]))
        ->assertSessionHas('name', 'Test User')
        ->assertSessionHas('email', 'test-org@example.com');

    withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => UserContext::Organization->value,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ])->assertRedirect(localized_route('users.show-introduction'));

    assertAuthenticated();
});

test('new users can not register without valid context', function () {
    from(localized_route('register', ['step' => 1]))
        ->post(localized_route('register-context'), [
            'context' => 'superadmin',
        ])
        ->assertRedirect(localized_route('register', ['step' => 1]))
        ->assertSessionHasErrors();
});

test('save user context request validation errors', function (array $state, array $errors) {
    post(localized_route('register-context'), $state)->assertSessionHasErrors($errors);
})->with('saveUserContextRequestValidationErrors');

test('users can register via invitation to (regulated) organization', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => 'test@example.com',
    ]);

    get(localized_route('register', [
        'context' => UserContext::RegulatedOrganization->value,
        'invitation' => 1,
        'email' => 'test@example.com',
    ]))
        ->assertSee('<input name="context" type="hidden" value="'.UserContext::RegulatedOrganization->value.'" />', false)
        ->assertSee('<input name="invitation" type="hidden" value="1" />', false)
        ->assertSee('<input name="email" type="hidden" value="test@example.com" />', false);

    post(localized_route('register-languages'), [
        'locale' => 'en',
        'context' => UserContext::RegulatedOrganization->value,
        'invitation' => 1,
        'email' => 'test@example.com',
    ])
        ->assertSessionHas('context', UserContext::RegulatedOrganization->value)
        ->assertSessionHas('isNewOrganizationContext', false)
        ->assertSessionHas('invitation', '1')
        ->assertSessionHas('email', 'test@example.com');

    withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => UserContext::RegulatedOrganization->value,
        'invitation' => 1,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ])->assertRedirect(localized_route('users.show-introduction'));

    assertAuthenticated();

    $user = Auth::user();

    expect($user->extra_attributes->invitation)->toEqual(1);

    expect($user->teamInvitation()->id)->toEqual($invitation->id);

    $user->update(['finished_introduction' => 1]);
    $user = $user->fresh();

    actingAs($user)->get(localized_route('dashboard'))
        ->assertSee('Invitation');
});

test('users with invitation are not prompted to create an organization or regulated organization', function () {
    $organization = Organization::factory()->create();
    $organizationUser = User::factory()->create(['context' => UserContext::Organization->value]);

    actingAs($organizationUser)
        ->get(localized_route('dashboard'))
        ->assertRedirect(localized_route('organizations.show-type-selection'));

    Invitation::factory()->create([
        'invitationable_id' => $organization->id,
        'invitationable_type' => get_class($organization),
        'email' => $organizationUser->email,
    ]);

    $organizationUser->refresh();

    expect($organizationUser->hasInvitation())->toBeTrue();

    actingAs($organizationUser)
        ->get(localized_route('dashboard'))
        ->assertOk();

    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);

    actingAs($regulatedOrganizationUser)
        ->get(localized_route('dashboard'))
        ->assertRedirect(localized_route('regulated-organizations.show-type-selection'));

    Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => $regulatedOrganizationUser->email,
    ]);

    $regulatedOrganizationUser->refresh();

    expect($regulatedOrganizationUser->hasInvitation())->toBeTrue();

    actingAs($regulatedOrganizationUser)
        ->get(localized_route('dashboard'))
        ->assertOk();
});

test('users can register via invitation to engagement', function () {
    $engagement = Engagement::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $engagement->id,
        'invitationable_type' => get_class($engagement),
        'email' => 'test@example.com',
        'role' => IndividualRole::ConsultationParticipant->value,
    ]);

    get(localized_route('register', [
        'context' => UserContext::Individual->value,
        'role' => IndividualRole::ConsultationParticipant->value,
        'invitation' => 1,
        'email' => 'test@example.com',
    ]))
        ->assertSee('<input name="context" type="hidden" value="'.UserContext::Individual->value.'" />', false)
        ->assertSee('<input name="invitation" type="hidden" value="1" />', false)
        ->assertSee('<input name="role" type="hidden" value="'.IndividualRole::ConsultationParticipant->value.'" />', false)
        ->assertSee('<input name="email" type="hidden" value="test@example.com" />', false);

    post(localized_route('register-languages'), [
        'locale' => 'en',
        'context' => UserContext::Individual->value,
        'invitation' => 1,
        'role' => IndividualRole::ConsultationParticipant->value,
        'email' => 'test@example.com',
    ])
        ->assertSessionHas('context', UserContext::Individual->value)
        ->assertSessionHas('invitation', '1')
        ->assertSessionHas('invited_role', IndividualRole::ConsultationParticipant->value)
        ->assertSessionHas('email', 'test@example.com');

    withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => UserContext::Individual->value,
        'invitation' => 1,
        'invited_role' => IndividualRole::ConsultationParticipant->value,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ])->assertRedirect(localized_route('users.show-introduction'));

    assertAuthenticated();

    $user = Auth::user();

    expect($user->extra_attributes->invitation)->toEqual(1);
    expect($user->extra_attributes->invited_role)->toEqual(IndividualRole::ConsultationParticipant->value);

    expect($user->participantInvitations()->pluck('id'))->toContain($invitation->id);

    $user = $user->fresh();

    expect($user->individual->roles)->toContain(IndividualRole::ConsultationParticipant->value);

    actingAs($user)->get(localized_route('individuals.show-role-edit'))
        ->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-participant" value="'.IndividualRole::ConsultationParticipant->value.'" aria-describedby="roles-participant-hint" checked  />', false);
});

test('platform admins are notified when a new individual user registers', function () {
    Notification::fake();

    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);

    withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test-individual-notify@example.com',
        'context' => UserContext::Individual->value,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ]);

    assertAuthenticated();

    Notification::assertSentTo(
        $admin,
        function (NewUserRegistered $notification, array $channels) {
            expect($channels)->toContain('mail', 'database');

            return true;
        }
    );
});

test('platform admins are notified when a new training participant registers', function () {
    Notification::fake();

    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);

    withSession([
        'locale' => 'en',
        'name' => 'Training User',
        'email' => 'test-training-notify@example.com',
        'context' => UserContext::TrainingParticipant->value,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ]);

    assertAuthenticated();

    Notification::assertSentTo(
        $admin,
        function (NewUserRegistered $notification, array $channels) {
            expect($channels)->toContain('mail', 'database');

            return true;
        }
    );
});

test('platform admins are not notified before organization details are created', function () {
    Notification::fake();

    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);

    withSession([
        'locale' => 'en',
        'name' => 'Org User',
        'email' => 'test-org-notify@example.com',
        'context' => UserContext::Organization->value,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ]);

    assertAuthenticated();

    Notification::assertNotSentTo($admin, NewUserRegistered::class);
});

test('platform admins are not notified before regulated organization details are created', function () {
    Notification::fake();

    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);

    withSession([
        'locale' => 'en',
        'name' => 'RegOrg User',
        'email' => 'test-regorg-notify@example.com',
        'context' => UserContext::RegulatedOrganization->value,
    ])->post(localized_route('register-store'), [
        'password' => 'correctHorse-batteryStaple7',
        'password_confirmation' => 'correctHorse-batteryStaple7',
        'accepted_terms_of_service' => true,
        'accepted_privacy_policy' => true,
    ]);

    assertAuthenticated();

    Notification::assertNotSentTo($admin, NewUserRegistered::class);
});

test('new user registered notification has correct content', function () {

    $user = User::factory()->hasIndividual()->create([
        'context' => UserContext::Individual->value,
    ]);

    $notification = new NewUserRegistered(user: $user);

    $mail = $notification->toMail();
    expect($mail->subject)->toBe(__('New user registered'));

    $array = $notification->toArray();
    expect($array['user_id'])->toBe($user->id);
});

test('platform admins can view new user registered notification', function () {

    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);
    $user = User::factory()->hasIndividual()->create([
        'context' => UserContext::Individual->value,
    ]);

    $admin->notify(new NewUserRegistered(user: $user));

    actingAs($admin)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('New user registered')
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee('Individual')
        ->assertDontSee(localized_route('individuals.show', $user->individual));
});

test('new user registered notification shows link when individual is publishable', function () {
    $admin = User::factory()->create(['context' => UserContext::Administrator->value]);

    $user = User::factory()
        ->hasIndividual([
            'roles' => [IndividualRole::AccessibilityConsultant->value],
            'bio' => ['en' => 'Test bio'],
            'consulting_services' => [ConsultingService::Analysis->value],
            'meeting_types' => [MeetingType::InPerson->value],
            'region' => 'ON',
        ])->create();

    expect($user->individual->isPublishable())->toBeTrue();
    $admin->notify(new NewUserRegistered(user: $user));

    actingAs($admin)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('New user registered')
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee('Individual')
        ->assertSee(localized_route('individuals.show', $user->individual));
});
