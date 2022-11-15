<?php

use App\Models\Choice;
use App\Models\Question;

test('choice belongs to a question', function () {
    $question = Question::factory()->create();
    $choice = Choice::factory()->for($question)->create();

    expect($choice->question->id)->toBe($question->id);
});
