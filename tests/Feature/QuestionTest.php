<?php

use App\Models\Question;
use App\Models\Quiz;

test('question belongs to many quizzes', function () {
    $quiz = Quiz::factory()->create();
    $anotherQuiz = Quiz::factory()->create();
    $question = Question::factory()->create();

    $quiz->questions()->attach($question);
    $anotherQuiz->questions()->attach($question);

    expect($question->quizzes()->count())->toBe(2);
});

test('deleting a quiz with a question belongs to other quizzes', function () {
    $quiz = Quiz::factory()->create();
    $anotherQuiz = Quiz::factory()->create();
    $question = Question::factory()->create();

    $quiz->questions()->attach($question);
    $anotherQuiz->questions()->attach($question);

    expect($question->quizzes()->count())->toBe(2);
    $quiz->delete();
    expect($question->quizzes()->count())->toBe(1);
});

test('deleting a question belongs to many quizzes', function () {
    $quiz = Quiz::factory()->create();
    $anotherQuiz = Quiz::factory()->create();
    $question = Question::factory()->create();

    $quiz->questions()->attach($question);
    $anotherQuiz->questions()->attach($question);

    $question->delete();
    expect($quiz->exists())->toBeTrue();
    expect($anotherQuiz->exists())->toBeTrue();
});
