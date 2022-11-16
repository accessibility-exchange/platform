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
