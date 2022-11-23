<?php

use App\Models\Course;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;

test('a quiz can belong to many users', function () {
    $quiz = Quiz::factory()->create();

    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    $user->quizzes()->attach($quiz);
    $anotherUser->quizzes()->attach($quiz);

    expect($quiz->users->contains($user))->toBeTrue();
    expect($quiz->users->contains($anotherUser))->toBeTrue();
});

test('a quiz can belong to a module', function () {
    $module = Module::factory()->for(Course::factory()->create())->create();
    $quiz = Quiz::factory()->for($module)->create();

    expect($quiz->module->id)->toBe($module->id);
});

test('a quiz can belong to a course', function () {
    $course = Course::factory()->create();
    $quiz = Quiz::factory()->for($course)->create();

    expect($quiz->course->id)->toBe($course->id);
});

test('a quiz can have many questions', function () {
    $quiz = Quiz::factory()->create();

    $firstQuestion = Question::factory()->for($quiz)->create();
    $secondQuestion = Question::factory()->for($quiz)->create();

    expect($quiz->questions->contains($firstQuestion))->toBeTrue();
    expect($quiz->questions->contains($secondQuestion))->toBeTrue();
});
