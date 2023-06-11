<?php

use App\Http\Livewire\ManageAccounts;
use App\Http\Livewire\ManageIndividualAccount;
use App\Http\Livewire\ManageOrganizationalAccount;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\AccountApproved;
use App\Notifications\AccountSuspended;
use App\Notifications\AccountUnsuspended;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->organizationUser = User::factory()->create(['context' => 'organization']);
    $this->secondaryOrganizationUser = User::factory()->create(['context' => 'organization']);
    $this->organization = Organization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
        'contact_person_email' => $this->organizationUser->email,
        'roles' => ['connector'],
    ]);
    $this->organization->users()->attach(
        $this->organizationUser,
        ['role' => 'admin']
    );
    $this->organization->users()->attach(
        $this->secondaryOrganizationUser,
        ['role' => 'admin']
    );

    $this->organizationalParticipantUser = User::factory()->create(['context' => 'organization']);
    $this->organizationalParticipant = Organization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
        'contact_person_email' => $this->organizationalParticipantUser->email,
        'roles' => ['participant'],
    ]);
    $this->organizationalParticipant->users()->attach(
        $this->organizationalParticipantUser,
        ['role' => 'admin']
    );

    $this->regulatedOrganizationUser = User::factory()->create(['context' => 'regulated-organization']);
    $this->secondaryRegulatedOrganizationUser = User::factory()->create(['context' => 'oregulated-rganization']);
    $this->regulatedOrganization = RegulatedOrganization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
        'contact_person_email' => $this->regulatedOrganizationUser->email,
    ]);
    $this->regulatedOrganization->users()->attach(
        $this->regulatedOrganizationUser,
        ['role' => 'admin']
    );
    $this->regulatedOrganization->users()->attach(
        $this->secondaryRegulatedOrganizationUser,
        ['role' => 'admin']
    );

    $this->individualUser = User::factory()->create([
        'oriented_at' => null,
    ]);

    $this->individual = $this->individualUser->individual;
    $this->individual->update(['roles' => ['connector', 'participant']]);
    $this->individual = $this->individual->fresh();

    $this->individualParticipantUser = User::factory()->create([
        'oriented_at' => null,
    ]);

    $this->individualParticipant = $this->individualParticipantUser->individual;
    $this->individualParticipant->update(['roles' => ['participant']]);
    $this->individualParticipant = $this->individualParticipant->fresh();
});

test('accounts appear with pending status before approval', function () {
    livewire(ManageAccounts::class)
        ->assertSeeInOrder([
            'Pending approval',
            'Pending approval',
            'Pending approval',
        ]);
});

test('accounts can be approved', function () {
    Notification::fake();

    livewire(ManageIndividualAccount::class, ['user' => $this->individualUser])
        ->call('approve')
        ->assertSee('Approved')
        ->assertEmitted('flashMessage');

    livewire(ManageIndividualAccount::class, ['user' => $this->individualParticipantUser])
        ->call('approve')
        ->assertSee('Approved')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organization])
        ->call('approve')
        ->assertSee('Approved')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organizationalParticipant])
        ->call('approve')
        ->assertSee('Approved')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->regulatedOrganization])
        ->call('approve')
        ->assertSee('Approved')
        ->assertEmitted('flashMessage');

    Notification::assertSentTo(
        $this->individualUser, function (AccountApproved $notification, $channels) {
            $this->assertStringContainsString('Your account has been approved', $notification->toMail($this->individual)->render());
            $this->assertStringContainsString('You are now able to publish your page and sign up for projects', $notification->toVonage($this->individual)->content);
            expect($notification->toArray($this->individualUser)['title'])->toEqual('Your account has been approved');

            return $notification->account->id === $this->individual->id;
        });

    Notification::assertSentTo(
        $this->individualParticipantUser, function (AccountApproved $notification, $channels) {
            $this->assertStringContainsString('You are now able to sign up for projects.', $notification->toMail($this->individualParticipant)->render());

            return $notification->account->id === $this->individualParticipant->id;
        });

    Notification::assertSentTo(
        $this->organization, function (AccountApproved $notification, $channels) {
            $this->assertStringContainsString('Your account has been approved', $notification->toMail($this->organization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange has been approved', $notification->toVonage($this->organization)->content);
            expect($notification->toArray($this->organization)['title'])->toEqual('Your account has been approved');

            return $notification->account->id === $this->organization->id;
        });

    Notification::assertSentTo(
        $this->organizationalParticipant, function (AccountApproved $notification, $channels) {
            $this->assertStringContainsString('You are now able to publish your page and take part in consultations', $notification->toMail($this->organizationalParticipant)->render());

            return $notification->account->id === $this->organizationalParticipant->id;
        });

    Notification::assertSentTo(
        $this->regulatedOrganization, function (AccountApproved $notification, $channels) {
            $this->assertStringContainsString('Your account has been approved', $notification->toMail($this->regulatedOrganization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange has been approved', $notification->toVonage($this->regulatedOrganization)->content);
            expect($notification->toArray($this->regulatedOrganization)['title'])->toEqual('Your account has been approved');

            return $notification->account->id === $this->regulatedOrganization->id;
        });
});

test('users can access approval notifications', function () {
    $this->individualUser->notify(new AccountApproved($this->individual));
    $this->organization->notify(new AccountApproved($this->organization));
    $this->regulatedOrganization->notify(new AccountApproved($this->regulatedOrganization));

    $response = $this->actingAs($this->individualUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account has been approved');

    $response = $this->actingAs($this->organizationUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account has been approved');

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account has been approved');
});

test('accounts can be suspended', function () {
    Notification::fake();

    livewire(ManageIndividualAccount::class, ['user' => $this->individualUser])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertEmitted('flashMessage');

    livewire(ManageIndividualAccount::class, ['user' => $this->individualParticipantUser])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organization])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organizationalParticipant])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->regulatedOrganization])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertEmitted('flashMessage');

    Notification::assertSentTo(
        $this->individualUser, function (AccountSuspended $notification, $channels) {
            $this->assertStringContainsString('Your account has been suspended', $notification->toMail($this->individual)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange has been suspended', $notification->toVonage($this->individual)->content);
            expect($notification->toArray($this->individualUser)['title'])->toEqual('Your account has been suspended');

            return $notification->account->id === $this->individual->id;
        });

    Notification::assertSentTo(
        $this->organization, function (AccountSuspended $notification, $channels) {
            $this->assertStringContainsString('Your account has been suspended', $notification->toMail($this->organization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange has been suspended', $notification->toVonage($this->organization)->content);
            expect($notification->toArray($this->organization)['title'])->toEqual('Your account has been suspended');

            return $notification->account->id === $this->organization->id;
        });

    Notification::assertNotSentTo($this->organizationUser, AccountSuspended::class);

    Notification::assertSentTo(
        $this->secondaryOrganizationUser, function (AccountSuspended $notification, $channels) {
            $this->assertStringContainsString('Your account has been suspended', $notification->toMail($this->organization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange has been suspended', $notification->toVonage($this->organization)->content);
            expect($notification->toArray($this->organization)['title'])->toEqual('Your account has been suspended');

            return $notification->account->id === $this->organization->id;
        });

    Notification::assertNotSentTo($this->regulatedOrganizationUser, AccountSuspended::class);

    Notification::assertSentTo(
        $this->secondaryRegulatedOrganizationUser, function (AccountSuspended $notification, $channels) {
            $this->assertStringContainsString('Your account has been suspended', $notification->toMail($this->regulatedOrganization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange has been suspended', $notification->toVonage($this->regulatedOrganization)->content);
            expect($notification->toArray($this->regulatedOrganization)['title'])->toEqual('Your account has been suspended');

            return $notification->account->id === $this->regulatedOrganization->id;
        });

    Notification::assertSentTo(
        $this->regulatedOrganization, function (AccountSuspended $notification, $channels) {
            $this->assertStringContainsString('Your account has been suspended', $notification->toMail($this->regulatedOrganization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange has been suspended', $notification->toVonage($this->regulatedOrganization)->content);
            expect($notification->toArray($this->regulatedOrganization)['title'])->toEqual('Your account has been suspended');

            return $notification->account->id === $this->regulatedOrganization->id;
        });

    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    expect($this->organizationUser->checkStatus('suspended'))->toBeTrue();
    expect($this->regulatedOrganizationUser->checkStatus('suspended'))->toBeTrue();
});

test('users can access suspension notifications', function () {
    $this->individualUser->notify(new AccountSuspended($this->individual));
    $this->organization->notify(new AccountSuspended($this->organization));
    $this->regulatedOrganization->notify(new AccountSuspended($this->regulatedOrganization));

    $response = $this->actingAs($this->individualUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account has been suspended');

    $response = $this->actingAs($this->organizationUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account has been suspended');

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account has been suspended');
});

test('accounts appear with suspended status when suspended', function () {
    foreach ([
        $this->organization,
        $this->regulatedOrganization,
        $this->individualUser,
    ] as $model) {
        $model->update([
            'suspended_at' => now(),
        ]);
    }

    livewire(ManageAccounts::class)
        ->assertSeeInOrder([
            'Suspended',
            'Suspended',
            'Suspended',
        ]);
});

test('accounts can be unsuspended', function () {
    Notification::fake();

    foreach ([
        $this->organization,
        $this->regulatedOrganization,
        $this->individualUser,
    ] as $model) {
        $model->update([
            'oriented_at' => now(),
            'suspended_at' => now(),
        ]);
    }

    foreach ([
        $this->organizationUser,
        $this->regulatedOrganizationUser,
    ] as $model) {
        $model->update([
            'suspended_at' => now(),
        ]);
    }

    foreach ([
        $this->organization,
        $this->regulatedOrganization,
    ] as $model) {
        $model->update([
            'validated_at' => now(),
        ]);
    }

    $this->organization = $this->organization->fresh();
    $this->regulatedOrganization = $this->regulatedOrganization->fresh();
    $this->individualUser = $this->individualUser->fresh();
    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    livewire(ManageIndividualAccount::class, ['user' => $this->individualUser])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertEmitted('flashMessage');

    livewire(ManageIndividualAccount::class, ['user' => $this->individualParticipantUser])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organization])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organizationalParticipant])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertEmitted('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->regulatedOrganization])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertEmitted('flashMessage');

    Notification::assertSentTo(
        $this->individualUser, function (AccountUnsuspended $notification, $channels) {
            $this->assertStringContainsString('Your account is no longer suspended', $notification->toMail($this->individual)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange is no longer suspended', $notification->toVonage($this->individual)->content);
            expect($notification->toArray($this->individualUser)['title'])->toEqual('Your account suspension has been lifted');

            return $notification->account->id === $this->individual->id;
        });

    Notification::assertSentTo(
        $this->organization, function (AccountUnsuspended $notification, $channels) {
            $this->assertStringContainsString('Your account is no longer suspended', $notification->toMail($this->organization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange is no longer suspended', $notification->toVonage($this->organization)->content);
            expect($notification->toArray($this->organization)['title'])->toEqual('Your account suspension has been lifted');

            return $notification->account->id === $this->organization->id;
        });

    Notification::assertNotSentTo($this->organizationUser, AccountUnsuspended::class);

    Notification::assertSentTo(
        $this->secondaryOrganizationUser, function (AccountUnsuspended $notification, $channels) {
            $this->assertStringContainsString('Your account is no longer suspended', $notification->toMail($this->organization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange is no longer suspended', $notification->toVonage($this->organization)->content);
            expect($notification->toArray($this->organization)['title'])->toEqual('Your account suspension has been lifted');

            return $notification->account->id === $this->organization->id;
        });

    Notification::assertSentTo(
        $this->regulatedOrganization, function (AccountUnsuspended $notification, $channels) {
            $this->assertStringContainsString('Your account is no longer suspended', $notification->toMail($this->regulatedOrganization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange is no longer suspended', $notification->toVonage($this->regulatedOrganization)->content);
            expect($notification->toArray($this->regulatedOrganization)['title'])->toEqual('Your account suspension has been lifted');

            return $notification->account->id === $this->regulatedOrganization->id;
        });

    Notification::assertNotSentTo($this->regulatedOrganizationUser, AccountUnsuspended::class);

    Notification::assertSentTo(
        $this->secondaryRegulatedOrganizationUser, function (AccountUnsuspended $notification, $channels) {
            $this->assertStringContainsString('Your account is no longer suspended', $notification->toMail($this->regulatedOrganization)->render());
            $this->assertStringContainsString('Your account on the Accessibility Exchange is no longer suspended', $notification->toVonage($this->regulatedOrganization)->content);
            expect($notification->toArray($this->regulatedOrganization)['title'])->toEqual('Your account suspension has been lifted');

            return $notification->account->id === $this->regulatedOrganization->id;
        });

    $this->organizationUser = $this->organizationUser->fresh();
    $this->regulatedOrganizationUser = $this->regulatedOrganizationUser->fresh();

    expect($this->organizationUser->checkStatus('suspended'))->toBeFalse();
    expect($this->regulatedOrganizationUser->checkStatus('suspended'))->toBeFalse();
});

test('users can access unsuspension notifications', function () {
    $this->individualUser->notify(new AccountUnsuspended($this->individual));
    $this->organization->notify(new AccountUnsuspended($this->organization));
    $this->regulatedOrganization->notify(new AccountUnsuspended($this->regulatedOrganization));

    $response = $this->actingAs($this->individualUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account is no longer suspended');

    $response = $this->actingAs($this->organizationUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account is no longer suspended');

    $response = $this->actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'));
    $response->assertOk();
    $response->assertSee('Your account is no longer suspended');
});

test('accounts can be searched', function () {
    livewire(ManageAccounts::class)
        ->assertSee($this->individual->name)
        ->assertSee($this->organization->name)
        ->assertSee($this->regulatedOrganization->name)
        ->set('searchQuery', $this->individual->name)
        ->call('search')
        ->assertSee($this->individual->name)
        ->assertDontSee($this->organization->name)
        ->assertDontSee($this->regulatedOrganization->name)
        ->assertSee('1 result')
        ->set('searchQuery', $this->organization->name)
        ->call('search')
        ->assertDontSee($this->individual->name)
        ->assertSee($this->organization->name)
        ->assertDontSee($this->regulatedOrganization->name)
        ->assertSee('1 result')
        ->set('searchQuery', $this->regulatedOrganization->name)
        ->call('search')
        ->assertDontSee($this->individual->name)
        ->assertDontSee($this->organization->name)
        ->assertSee($this->regulatedOrganization->name)
        ->assertSee('1 result');
});
