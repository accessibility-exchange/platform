<?php

use App\Enums\UserContext;
use App\Models\Course;
use App\Models\Module;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Quiz;
use App\Models\RegulatedOrganization;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

test('users can view the introduction', function () {
    $user = User::factory()->create();
    $user->update(['context' => 'individual']);

    actingAs($user)->get(localized_route('users.show-introduction'))
        ->assertOk()
        ->assertSee(str_replace('"', '', json_encode('https://vimeo.com/850308866/22cf4718fc')));

    actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ])
        ->assertRedirect(localized_route('individuals.show-role-selection'));

    $user = $user->fresh();

    expect($user->finished_introduction)->toBeTrue();

    $user->update(['context' => 'organization']);

    actingAs($user)->get(localized_route('users.show-introduction'))
        ->assertOk()
        ->assertSee(str_replace('"', '', json_encode('https://vimeo.com/850308900/39c5bb60a7')));

    actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ])
        ->assertRedirect(localized_route('organizations.show-type-selection'));

    actingAs($user)->get(localized_route('dashboard'))
        ->assertRedirect(localized_route('organizations.show-type-selection'));

    $user->update(['context' => 'regulated-organization']);

    actingAs($user)->get(localized_route('users.show-introduction'))
        ->assertOk()
        ->assertSee(str_replace('"', '', json_encode('https://vimeo.com/850308924/cab1e34418')));

    actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ])
        ->assertRedirect(localized_route('regulated-organizations.show-type-selection'));

    actingAs($user)->get(localized_route('dashboard'))
        ->assertRedirect(localized_route('regulated-organizations.show-type-selection'));

    $user->update(['context' => 'training-participant']);

    actingAs($user)->get(localized_route('users.show-introduction'))
        ->assertOk();

    actingAs($user)
        ->from(localized_route('users.show-introduction'))
        ->put(localized_route('users.update-introduction-status'), [
            'finished_introduction' => 1,
        ])
        ->assertRedirect(localized_route('dashboard'));
});

test('user’s contact methods can be retrieved', function () {
    $user = User::factory()->create();

    expect($user->contact_methods)->toEqual(['email']);

    $user->update([
        'phone' => '19024445555',
    ]);

    expect($user->fresh()->contact_methods)->toEqual(['email', 'phone']);

    expect($user->routeNotificationForVonage(new \Illuminate\Notifications\Notification()))->toEqual($user->phone);

    $user->update([
        'preferred_contact_person' => 'support-person',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
    ]);

    expect($user->fresh()->contact_methods)->toEqual(['email']);

    $user->update([
        'support_person_phone' => '19024445555',
    ]);

    expect($user->fresh()->contact_methods)->toEqual(['email', 'phone']);

    $user->update([
        'support_person_email' => null,
    ]);

    $user = $user->fresh();

    expect($user->contact_methods)->toEqual(['phone']);

    expect($user->routeNotificationForVonage(new \Illuminate\Notifications\Notification()))->toEqual($user->support_person_phone);
});

test('user’s vrs requirement can be retrieved', function () {
    $user = User::factory()->create([
        'preferred_contact_person' => 'me',
        'vrs' => true,
        'support_person_vrs' => false,
    ]);

    expect($user->requires_vrs)->toBeTrue();

    $user->update(['preferred_contact_person' => 'support-person']);

    expect($user->requires_vrs)->toBeFalse();
});

test('individual’s contact methods can be retrieved', function () {
    $user = User::factory()->create([
        'name' => 'Jonny Appleseed',
        'email' => 'jonny@example.com',
        'phone' => '9055555555',
        'vrs' => true,
        'preferred_contact_person' => 'me',
        'preferred_contact_method' => 'email',
        'support_person_name' => 'Jenny Appleseed',
        'support_person_email' => 'jenny@example.com',
        'support_person_phone' => '9054444444',
        'support_person_vrs' => false,
    ]);

    $individual = $user->individual;

    expect($individual->preferred_contact_person)->toEqual('me');
    expect($individual->preferred_contact_method)->toEqual('email');
    expect($individual->contact_email)->toEqual('jonny@example.com');
    expect($individual->contact_phone)->toEqual('1 (905) 555-5555');
    expect($individual->contact_vrs)->toBeTrue();

    $user->update(['preferred_contact_person' => 'support-person']);

    $individual->refresh();

    expect($individual->preferred_contact_person)->toEqual('support-person');
    expect($individual->contact_email)->toEqual('jenny@example.com');
    expect($individual->contact_phone)->toEqual('1 (905) 444-4444');
    expect($individual->contact_vrs)->toBeFalse();

    $user->update(['preferred_contact_method' => 'phone']);

    $individual->refresh();

    expect($individual->preferred_contact_method)->toEqual('phone');
});

test('user extra attributes and notification settings can be queried', function () {
    $users = User::factory()->count(5)->create([
        'extra_attributes' => [
            'invited_role' => 'participant',
        ],
        'notification_settings' => [
            'updates' => [
                'channels' => [
                    'contact',
                ],
            ],
        ],
    ]);

    $invitedParticipants = User::withExtraAttributes('invited_role', 'participant')->get();

    expect($invitedParticipants)->toHaveCount(5);

    foreach ($invitedParticipants as $participant) {
        expect($participant->extra_attributes->invited_role)->toEqual('participant');
    }

    $updateNotificationUsers = User::withNotificationSettings('updates->channels', '["contact"]')->get();

    expect($updateNotificationUsers)->toHaveCount(5);

    foreach ($updateNotificationUsers as $user) {
        expect($user->notification_settings->updates['channels'])->toContain('contact');
    }
});

test('user is only admin of an organization', function () {
    $user = User::factory()->create(['context' => 'organization']);
    $anotherUser = User::factory()->create(['context' => 'organization']);

    $organization = Organization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($anotherUser, ['role' => 'admin'])
        ->create();

    expect($user->isOnlyAdministratorOfOrganization())->toBeFalse();

    $anotherUser->delete();

    expect($user->isOnlyAdministratorOfOrganization())->toBeTrue();
});

test('user is only admin of a regulated organization', function () {
    $user = User::factory()->create(['context' => 'regulated-organization']);
    $anotherUser = User::factory()->create(['context' => 'regulated-organization']);

    $organization = RegulatedOrganization::factory()
        ->hasAttached($user, ['role' => 'admin'])
        ->hasAttached($anotherUser, ['role' => 'admin'])
        ->create();

    expect($user->isOnlyAdministratorOfRegulatedOrganization())->toBeFalse();

    $anotherUser->delete();

    expect($user->isOnlyAdministratorOfRegulatedOrganization())->toBeTrue();
});

test('user’s two factor status can be retrieved', function () {
    $user = User::factory()->create();
    expect($user->twoFactorAuthEnabled())->tobeFalse();
});

test('administrative user can be retrieved via query scope', function () {
    $user = User::factory()->create();
    $adminUser = User::factory()->create(['context' => 'administrator']);

    $users = User::all()->pluck('id')->toArray();
    /** @see https://github.com/spatie/laravel-ciphersweet/discussions/51 */
    $administrators = User::whereAdministrator()->get()->pluck('id')->toArray();

    foreach ([$user->id, $adminUser->id] as $id) {
        expect($users)->toContain($id);
    }
    expect($administrators)->toContain($adminUser->id);
    expect(in_array($user->id, $administrators))->toBeFalse();
});

test('user email with mixed case is saved as lower case', function () {
    $user = User::factory()->create(['email' => 'John.Smith@example.com']);
    expect($user->email)->toEqual('john.smith@example.com');
});

test('user has pivot tables for module, course and quiz', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $module = Module::factory()->for($course)->create();
    $quiz = Quiz::factory()->for($course)->create();

    $user->modules()->sync($module->id);
    $user->quizzes()->sync($quiz->id);
    $user->courses()->sync($course->id);

    assertDatabaseHas('module_user', [
        'module_id' => $module->id,
        'user_id' => $user->id,
    ]);

    assertDatabaseHas('quiz_user', [
        'quiz_id' => $quiz->id,
        'user_id' => $user->id,
    ]);

    assertDatabaseHas('course_user', [
        'course_id' => $course->id,
        'user_id' => $user->id,
    ]);
});

test('users can view inprogress and completed Courses from their dashbaord', function () {
    $user = User::factory()->create();
    $inProgressCourse = Course::factory()->create(['author' => 'Author 1']);
    $completedCourse = Course::factory()->create(['author' => 'Author 2']);

    Quiz::factory()->for(Course::find($inProgressCourse->id))->create();
    Quiz::factory()->for(Course::find($completedCourse->id))->create();

    $user->courses()->attach($inProgressCourse->id, ['started_at' => now()]);
    $user->courses()->attach($completedCourse->id, ['received_certificate_at' => now()]);

    actingAs($user)->get(localized_route('dashboard.trainings'))
        ->assertOk()
        ->assertSee('Author 1')
        ->assertSee('Author 2')
        ->assertSee('In progress')
        ->assertSee('Completed');
});

test('User hasTasksToComplete()', function ($data, $expected) {
    $orgType = match ($data['user']['context'] ?? null) {
        UserContext::Organization->value => Organization::class,
        UserContext::RegulatedOrganization->value => RegulatedOrganization::class,
        default => null
    };

    $user = User::factory()->create($data['user']);

    if (isset($data['individual'])) {
        $user->individual->fill($data['individual']);
        $user->individual->save();

        $user->refresh();
    } elseif ($orgType && isset($data['org'])) {
        $org = $orgType::factory()
            ->hasAttached($user, ['role' => $data['orgRole'] ?? 'admin'])
            ->create($data['org']);

        if (isset($data['withProject'])) {
            $project = Project::factory()->create();

            $org->projects()->save($project);
        }
    }

    expect($user->hasTasksToComplete())->toEqual($expected);
})->with('userHasTasksToComplete');

test('user status checks return expected state', function () {
    $user = User::factory()->create([
        'oriented_at' => null,
        'suspended_at' => null,
        'dismissed_customize_prompt_at' => null,
    ]);

    expect($user->checkStatus('pending'))->toBeTrue();
    expect($user->checkStatus('approved'))->toBeFalse();
    expect($user->checkStatus('suspended'))->toBeFalse();
    expect($user->checkStatus('dismissedCustomizationPrompt'))->toBeFalse();

    $user->oriented_at = now();
    $user->save();

    expect($user->checkStatus('pending'))->toBeFalse();
    expect($user->checkStatus('approved'))->toBeTrue();

    $user->suspended_at = now();
    $user->save();

    expect($user->checkStatus('suspended'))->toBeTrue();

    $user->dismissed_customize_prompt_at = now();
    $user->save();

    expect($user->checkStatus('dismissedCustomizationPrompt'))->toBeTrue();
});

test('users’ preferred locale is set based on their locale', function () {
    $user = User::factory()->create([
        'locale' => 'en',
    ]);

    expect($user->preferredLocale())->toBe('en');

    $user->locale = 'asl';
    $user->save();

    expect($user->preferredLocale())->toBe('en');

    $user->locale = 'fr';
    $user->save();

    expect($user->preferredLocale())->toBe('fr');

    $user->locale = 'lsq';
    $user->save();

    expect($user->preferredLocale())->toBe('fr');
});
