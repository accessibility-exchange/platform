<?php

use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\RegulatedOrganization;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get(localized_route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    User::factory()->create(['email' => 'me@here.com']);

    $response = $this->from(localized_route('register', ['step' => 1]))
        ->post(localized_route('register-languages'), [
            'locale' => 'en',
            'signed_language' => 'ase',
        ]);
    $response->assertRedirect(localized_route('register', ['step' => 2]));
    $response->assertSessionHas('locale', 'en');
    $response->assertSessionHas('signed_language', 'ase');

    $response = $this->from(localized_route('register', ['step' => 2]))
        ->withSession([
            'locale' => 'en',
            'signed_language' => 'ase',
        ])
        ->post(localized_route('register-context'), [
            'context' => 'individual',
        ]);
    $response->assertRedirect(localized_route('register', ['step' => 3]));
    $response->assertSessionHas('context', 'individual');

    $response = $this->from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'signed_language' => 'ase',
            'context' => 'individual',
        ])
        ->post(localized_route('register-details'), [
            'name' => 'Test User',
            'email' => 'me@here.com',
        ]);

    $response->assertSessionHasErrors(['email']);
    $response->assertRedirect(localized_route('register', ['step' => 3]));

    $response = $this->from(localized_route('register', ['step' => 3]))
        ->withSession([
            'locale' => 'en',
            'signed_language' => 'ase',
            'context' => 'individual',
        ])
        ->post(localized_route('register-details'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response->assertRedirect(localized_route('register', ['step' => 4]));
    $response->assertSessionHas('name', 'Test User');
    $response->assertSessionHas('email', 'test@example.com');

    $response = $this->withSession([
        'locale' => 'en',
        'signed_language' => 'ase',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => 'individual',
    ])->post(localized_route('register-store'), [
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(localized_route('users.show-introduction'));
});

test('new users can not register without valid context', function () {
    $response = $this->from(localized_route('register', ['step' => 1]))
        ->post(localized_route('register-context'), [
            'context' => 'superadmin',
        ]);
    $response->assertRedirect(localized_route('register', ['step' => 1]));
    $response->assertSessionHasErrors();
});

test('users can register via invitation to (regulated) organization', function () {
    $regulatedOrganization = RegulatedOrganization::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $regulatedOrganization->id,
        'invitationable_type' => get_class($regulatedOrganization),
        'email' => 'test@example.com',
    ]);

    $response = $this->get(localized_route('register', [
        'context' => 'regulated-organization',
        'invitation' => 1,
    ]));

    $response->assertSee('<input name="context" type="hidden" value="regulated-organization" />', false);
    $response->assertSee('<input name="invitation" type="hidden" value="1" />', false);

    $response = $this->post(localized_route('register-languages'), [
        'locale' => 'en',
        'context' => 'regulated-organization',
        'invitation' => 1,
    ]);

    $response->assertSessionHas('context', 'regulated-organization');
    $response->assertSessionHas('invitation', '1');

    $response = $this->withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => 'regulated-organization',
        'invitation' => 1,
    ])->post(localized_route('register-store'), [
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(localized_route('users.show-introduction'));

    $user = Auth::user();

    expect($user->extra_attributes->invitation)->toEqual(1);

    expect($user->invitation()->id)->toEqual($invitation->id);

    $user->finished_introduction = 1;
    $user->save();

    $user = $user->fresh();

    $response = $this->actingAs($user)->get(localized_route('dashboard'));
    $response->assertSee('Invitation');
});

test('users can register via invitation to engagement', function () {
    $engagement = Engagement::factory()->create();
    $invitation = Invitation::factory()->create([
        'invitationable_id' => $engagement->id,
        'invitationable_type' => get_class($engagement),
        'email' => 'test@example.com',
    ]);

    $response = $this->get(localized_route('register', [
        'context' => 'individual',
        'role' => 'participant',
        'invitation' => 1,
    ]));

    $response->assertSee('<input name="context" type="hidden" value="individual" />', false);
    $response->assertSee('<input name="invitation" type="hidden" value="1" />', false);
    $response->assertSee('<input name="role" type="hidden" value="participant" />', false);

    $response = $this->post(localized_route('register-languages'), [
        'locale' => 'en',
        'context' => 'individual',
        'invitation' => 1,
        'role' => 'participant',
    ]);

    $response->assertSessionHas('context', 'individual');
    $response->assertSessionHas('invitation', '1');
    $response->assertSessionHas('roles', ['participant']);

    $response = $this->withSession([
        'locale' => 'en',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'context' => 'individual',
        'invitation' => 1,
        'role' => 'participant',
    ])->post(localized_route('register-store'), [
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(localized_route('users.show-introduction'));

    $user = Auth::user();

    expect($user->extra_attributes->invitation)->toEqual(1);
    expect($user->extra_attributes->roles)->toEqual(['participant']);

    expect($user->invitation()->id)->toEqual($invitation->id);

    $user = $user->fresh();

    expect($user->individual->roles)->toContain('participant');

    $response = $this->actingAs($user)->get(localized_route('individuals.show-role-edit'));
    $response->assertSee('<input x-model="roles" type="checkbox" name="roles[]" id="roles-participant" value="participant" aria-describedby="roles-participant-hint" checked  />', false);
});
