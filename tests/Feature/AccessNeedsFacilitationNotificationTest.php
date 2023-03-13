<?php

use App\Enums\ContactPerson;
use App\Models\Engagement;
use App\Models\User;
use App\Notifications\AccessNeedsFacilitationRequested;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'email_verified_at' => now(),
        'context' => 'administrator',
    ]);

    $this->engagement = Engagement::factory()->create([
        'recruitment' => 'open-call',
        'signup_by_date' => now()->add(1, 'month')->format('Y-m-d'),
    ]);

    $this->participantUser = User::factory()->create([
        'support_person_name' => $this->faker->name(),
        'support_person_email' => function (array $attributes) {
            return Str::slug($attributes['support_person_name']).'@'.$this->faker->safeEmailDomain();
        },
        'support_person_phone' => '9054444444',
        'phone' => '9055555555',
    ]);
    $this->participantUser->individual->update(['roles' => ['participant'], 'region' => 'NS', 'locality' => 'Bridgewater']);
    $this->participant = $this->participantUser->individual->fresh();
});

test('Notification data', function ($userData) {
    Notification::fake();

    $this->participantUser->fill($userData);
    $this->participantUser->save();
    $this->participantUser->refresh();

    $administrators = User::whereAdministrator()->get();
    Notification::send($administrators, new AccessNeedsFacilitationRequested($this->participantUser, $this->engagement));

    Notification::assertSentTo(
        $this->admin,
        function (AccessNeedsFacilitationRequested $notification, $channels) {
            expect($notification->toMail()->subject)->toBe(__(':name requires access needs facilitation', ['name' => $this->participant->name]));
            $renderedMail = $notification->toMail()->render();

            $this->assertStringContainsString(__('Please contact :name to facilitate their access needs being met on the engagement', ['name' => $this->participant->name]), $renderedMail);
            $this->assertStringContainsString(localized_route('engagements.show', $this->engagement), $renderedMail);
            $this->assertStringContainsString($this->engagement->name.'</a>', $renderedMail);

            expect($notification->toArray($this->admin)['individual_id'])->toEqual($this->participant->id);
            expect($notification->toArray($this->admin)['engagement_id'])->toEqual($this->engagement->id);

            // Test contact header
            $expectedContactHeader = $this->participant->preferred_contact_person === ContactPerson::Me->value ?
                __('Contact :name', ['name' => $this->participant->name]) :
                __('Contact :name’s support person, :support_person_name', ['name' => $this->participant->name, 'support_person_name' => $this->participantUser->support_person_name]);

            $this->assertStringContainsString($expectedContactHeader, $renderedMail);

            // Test phone
            if ($this->participant->contact_phone) {
                $this->assertStringContainsString(__('Phone'), $renderedMail);
                $this->assertStringContainsString($this->participant->contact_phone, $renderedMail);
            }

            // Test email
            if ($this->participant->contact_email) {
                $this->assertStringContainsString(__('Email'), $renderedMail);
                $this->assertStringContainsString($this->participant->contact_email, $renderedMail);
            }

            // Test VRS
            if ($this->participant->contact_vrs) {
                $this->assertStringContainsString(__('requires VRS'), $renderedMail);
            }

            // Test preferred contact
            if ($this->participant->contact_phone && $this->participant->contact_email) {
                $this->assertStringContainsString(__('preferred'), $renderedMail);
            }

            // Check notification properties
            expect($notification->engagement->id)->toBe($this->engagement->id);

            return $notification->user->id === $this->participant->user->id;
        }
    );
})->with('accessNeedsFacilitationNotification');

test('Notification view', function ($userData) {
    $this->participantUser->fill($userData);
    $this->participantUser->save();
    $this->participantUser->refresh();

    // Send notification
    $administrators = User::whereAdministrator()->get();
    Notification::send($administrators, new AccessNeedsFacilitationRequested($this->participantUser, $this->engagement));

    $toSee = [];
    $dontSee = [];

    // Test contact header
    $toSee[] = $this->participant->preferred_contact_person === ContactPerson::Me->value ?
        __('Contact :name', ['name' => $this->participant->name]) :
        __('Contact :name’s support person, :support_person_name', ['name' => $this->participant->name, 'support_person_name' => $this->participantUser->support_person_name]);

    // Test email
    if ($this->participant->contact_email) {
        $toSee[] = __('Email');

        if ($this->participant->preferred_contact_method === 'email' && $this->participant->contact_phone) {
            $toSee[] = __('preferred');
        }

        $toSee[] = $this->participant->contact_email;
    }

    // Test phone
    if ($this->participant->contact_phone) {
        $toSee[] = __('Phone');

        if ($this->participant->preferred_contact_method === 'phone' && $this->participant->contact_email) {
            $toSee[] = __('preferred');
        }

        $toSee[] = $this->participant->contact_phone;
    }

    // Test VRS
    if ($this->participant->contact_vrs) {
        $toSee[] = __('requires VRS');
    } else {
        $dontSee[] = __('requires VRS');
    }

    // Test preferred contact
    if (! $this->participant->contact_phone || ! $this->participant->contact_email) {
        $dontSee[] = __('preferred');
    }

    // End of notification
    $toSee[] = __('Mark as read');

    $response = $this->actingAs($this->admin)->get(localized_route('dashboard.notifications'));
    $response->assertSeeTextInOrder($toSee);

    foreach ($dontSee as $dontSeeText) {
        $response->assertDontSeeText($dontSeeText);
    }

    // Remove notification
    $this->admin->notifications()->delete();
})->with('accessNeedsFacilitationNotification');
