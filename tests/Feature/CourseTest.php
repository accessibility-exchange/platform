<?php

use App\Models\Course;
use App\Models\Module;
use App\Models\Organization;
use App\Models\Quiz;
use App\Models\User;

test('a course can belongs to many users', function () {
    $course = Course::factory()->create();

    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    $user->courses()->attach($course);
    $anotherUser->courses()->attach($course);

    expect($course->users->contains($user))->toBeTrue();
    expect($course->users->contains($anotherUser))->toBeTrue();
});

test('a course has a quiz', function () {
    $course = Course::factory()->create();
    $quiz = Quiz::factory()->for($course)->create();

    expect($course->quiz->id)->toBe($quiz->id);
});

test('a course has many modules', function () {
    $course = Course::factory()->create();
    $moduleOne = Module::factory()->for($course)->create();
    $moduleTwo = Module::factory()->for($course)->create();

    expect($course->modules->contains($moduleOne))->toBeTrue();
    expect($course->modules->contains($moduleTwo))->toBeTrue();
});

test('a course is belong to an organizations', function () {
    $organization = Organization::factory()->create();
    $course = Course::factory()->for($organization)->create();

    expect($course->organization->id)->toBe($organization->id);
});

test('users can take the quiz for courses once they finish all the modules in the course', function () {
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $module = Module::factory()->for($course)->create();
    Quiz::factory()->for($course)->create();
    $response = $this->actingAs($user)->get(localized_route('courses.show', $course));
    $response->assertOk();
    $response->assertSee(__('not available yet'));
    $response->assertDontSee(__('completed'));

    $user->modules()->attach(
        $module->id, [
            'started_content_at' => now(),
            'finished_content_at' => now(),
        ]
    );

    $user->courses()->attach(
        $course->id, [
            'finished_at' => now(),
        ]
    );

    $user->refresh();

    $response = $this->actingAs($user)->get(localized_route('courses.show', $course));
    $response->assertOk();
    $response->assertSessionHasNoErrors();
    $response->assertDontSee(__('not available yet'));
    $response->assertSee(__('completed'));
});
