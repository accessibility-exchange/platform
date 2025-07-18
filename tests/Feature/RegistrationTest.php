<?php

use App\Enums\IndividualRole;
use App\Enums\UserContext;
use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        ->assertSee('<input name="context" type="hidden" value="regulated-organization" />', false)
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
        ->assertSee('<input name="context" type="hidden" value="individual" />', false)
        ->assertSee('<input name="invitation" type="hidden" value="1" />', false)
        ->assertSee('<input name="role" type="hidden" value="participant" />', false)
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
        ->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-participant" value="participant" aria-describedby="roles-participant-hint" checked  />', false);
});
