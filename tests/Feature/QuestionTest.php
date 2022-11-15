<?php

use App\Models\Choice;
use App\Models\Question;
use App\Models\Quiz;

test('question belongs to a quiz', function () {
    $quiz = Quiz::factory()->create();
    $question = Question::factory()->for($quiz)->create();

    expect($question->quiz->id)->toBe($quiz->id);
});

test('a question has many choices', function () {
    $question = Question::factory()->create();

    $firstChoice = Choice::factory()->for($question)->create();
    $secondChoice = Choice::factory()->for($question)->create();

    expect($question->choices->contains($firstChoice))->toBeTrue();
    expect($question->choices->contains($secondChoice))->toBeTrue();
});
