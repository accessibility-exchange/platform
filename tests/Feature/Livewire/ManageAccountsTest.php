<?php

use App\Enums\IndividualRole;
use App\Enums\OrganizationRole;
use App\Enums\TeamRole;
use App\Enums\UserContext;
use App\Livewire\ManageAccounts;
use App\Livewire\ManageIndividualAccount;
use App\Livewire\ManageOrganizationalAccount;
use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Notifications\AccountApproved;
use App\Notifications\AccountSuspended;
use App\Notifications\AccountUnsuspended;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->organizationUser = User::factory()->create(['context' => UserContext::Organization->value]);
    $this->secondaryOrganizationUser = User::factory()->create(['context' => UserContext::Organization->value]);
    $this->organization = Organization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
        'contact_person_email' => $this->organizationUser->email,
        'roles' => [OrganizationRole::CommunityConnector->value],
    ]);
    $this->organization->users()->attach(
        $this->organizationUser,
        ['role' => TeamRole::Administrator->value]
    );
    $this->organization->users()->attach(
        $this->secondaryOrganizationUser,
        ['role' => TeamRole::Administrator->value]
    );

    $this->organizationalParticipantUser = User::factory()->create(['context' => UserContext::Organization->value]);
    $this->organizationalParticipant = Organization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
        'contact_person_email' => $this->organizationalParticipantUser->email,
        'roles' => [OrganizationRole::ConsultationParticipant->value],
    ]);
    $this->organizationalParticipant->users()->attach(
        $this->organizationalParticipantUser,
        ['role' => TeamRole::Administrator->value]
    );

    $this->regulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $this->secondaryRegulatedOrganizationUser = User::factory()->create(['context' => UserContext::RegulatedOrganization->value]);
    $this->regulatedOrganization = RegulatedOrganization::factory()->create([
        'oriented_at' => null,
        'validated_at' => null,
        'contact_person_email' => $this->regulatedOrganizationUser->email,
    ]);
    $this->regulatedOrganization->users()->attach(
        $this->regulatedOrganizationUser,
        ['role' => TeamRole::Administrator->value]
    );
    $this->regulatedOrganization->users()->attach(
        $this->secondaryRegulatedOrganizationUser,
        ['role' => TeamRole::Administrator->value]
    );

    $this->individualUser = User::factory()->create([
        'oriented_at' => null,
    ]);

    $this->individual = Individual::factory()
        ->for($this->individualUser)
        ->create([
            'roles' => [
                IndividualRole::CommunityConnector->value,
                IndividualRole::ConsultationParticipant->value,
            ],
        ]);

    $this->individualParticipantUser = User::factory()
        ->has(Individual::factory())
        ->create(['oriented_at' => null]);

    $this->individualParticipant = $this->individualParticipantUser->individual;
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
        ->assertDispatched('flashMessage');

    livewire(ManageIndividualAccount::class, ['user' => $this->individualParticipantUser])
        ->call('approve')
        ->assertSee('Approved')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organization])
        ->call('approve')
        ->assertSee('Approved')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organizationalParticipant])
        ->call('approve')
        ->assertSee('Approved')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->regulatedOrganization])
        ->call('approve')
        ->assertSee('Approved')
        ->assertDispatched('flashMessage');

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

    actingAs($this->individualUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account has been approved');

    actingAs($this->organizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account has been approved');

    actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account has been approved');
});

test('accounts can be suspended', function () {
    Notification::fake();

    livewire(ManageIndividualAccount::class, ['user' => $this->individualUser])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertDispatched('flashMessage');

    livewire(ManageIndividualAccount::class, ['user' => $this->individualParticipantUser])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organization])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organizationalParticipant])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->regulatedOrganization])
        ->call('suspend')
        ->assertSee('Suspended')
        ->assertDispatched('flashMessage');

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

    actingAs($this->individualUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account has been suspended');

    actingAs($this->organizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account has been suspended');

    actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account has been suspended');
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
        ->assertDispatched('flashMessage');

    livewire(ManageIndividualAccount::class, ['user' => $this->individualParticipantUser])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organization])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->organizationalParticipant])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertDispatched('flashMessage');

    livewire(ManageOrganizationalAccount::class, ['account' => $this->regulatedOrganization])
        ->call('unsuspend')
        ->assertDontSee('Suspended')
        ->assertDispatched('flashMessage');

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

    actingAs($this->individualUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account is no longer suspended');

    actingAs($this->organizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account is no longer suspended');

    actingAs($this->regulatedOrganizationUser)->get(localized_route('dashboard.notifications'))
        ->assertOk()
        ->assertSee('Your account is no longer suspended');
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

test('flash method with interpretation name', function () {
    livewire(ManageAccounts::class)
        ->call('flash', 'Test message', 'test_interpretation_name')
        ->assertDispatched('clear-flash-message')
        ->assertSessionHas('_flash.new.0', 'message')
        ->assertSessionHas('_flash.new.1', 'message-interpretation')
        ->assertDispatched('add-flash-message');
});

test('flash method without interpretation name', function () {
    livewire(ManageAccounts::class)
        ->call('flash', 'Test message')
        ->assertDispatched('clear-flash-message')
        ->assertSessionHas('_flash.new.0', 'message')
        ->assertSessionMissing('_flash.new.1')
        ->assertDispatched('add-flash-message');
});

test('accounts can be filtered by account type', function () {
    livewire(ManageAccounts::class)
        ->set('accountType', 'Individual')
        ->call('search')
        ->assertSet('accountType', 'Individual')
        ->assertSee('2 Individual accounts.')

        ->set('accountType', 'Organization')
        ->call('search')
        ->assertSet('accountType', 'Organization')
        ->assertSee('2 Organization accounts.')

        ->set('accountType', 'Regulated organization')
        ->call('search')
        ->assertSet('accountType', 'Regulated organization')
        ->assertSee('1 Regulated organization accounts.');
});

test('accounts can be searched within a selected account type', function () {
    livewire(ManageAccounts::class)
        ->set('accountType', 'Organization')
        ->set('searchQuery', $this->organization->name)
        ->call('search')
        ->assertSet('accountType', 'Organization')
        ->assertSet('searchQuery', $this->organization->name)
        ->assertSee('1 results for "'.$this->organization->name.'" in Organization accounts.');
});
