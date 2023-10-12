<?php

use App\Models\Choice;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('a quiz can belong to many users', function () {
    $quiz = Quiz::factory()->create();

    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    $user->quizzes()->attach($quiz);
    $anotherUser->quizzes()->attach($quiz);

    expect($quiz->users->contains($user))->toBeTrue();
    expect($quiz->users->contains($anotherUser))->toBeTrue();
});

test('a quiz can belong to a course', function () {
    $course = Course::factory()->create();
    $quiz = Quiz::factory()->for($course)->create();

    expect($quiz->course->id)->toBe($course->id);
});

test('a quiz can have many questions', function () {
    $quiz = Quiz::factory()->create();

    $firstQuestion = Question::factory()->create();
    $secondQuestion = Question::factory()->create();

    $quiz->questions()->attach($firstQuestion);
    $quiz->questions()->attach($secondQuestion);

    expect($quiz->questions->contains($firstQuestion))->toBeTrue();
    expect($quiz->questions->contains($secondQuestion))->toBeTrue();
});

test('a quiz can have questions in order specified', function () {
    $quiz = Quiz::factory()->create(['order' => [4, 3]]);

    $firstQuestionWithOrder = Question::factory()->create(['id' => 4]);
    $secondQuestionWithOrder = Question::factory()->create(['id' => 3]);
    $thirdQuestionWithoutOrder = Question::factory()->create(['id' => 5]);

    $quiz->questions()->attach($firstQuestionWithOrder);
    $quiz->questions()->attach($secondQuestionWithOrder);
    $quiz->questions()->attach($thirdQuestionWithoutOrder);

    $orderedQuestions = $quiz->getQuestionsInOrder();
    expect($orderedQuestions)->toHaveCount(3);
    expect($orderedQuestions[0]->id)->toBe($firstQuestionWithOrder->id);
    expect($orderedQuestions[1]->id)->toBe($secondQuestionWithOrder->id);
    expect($orderedQuestions[2]->id)->toBe($thirdQuestionWithoutOrder->id);
});

test('users can view quiz results on finishing it', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $user->courses()->attach(
        $course->id, ['started_at' => now()]
    );
    $quiz = Quiz::factory()->for($course)->create();
    $question = Question::factory()->create(['choices' => ['en' => [['label' => 'first choice', 'value' => 0], ['label' => 'second choice', 'value' => 1], ['label' => 'third choice', 'value' => 2]]]]);
    $quiz->questions()->attach($question);

    $response = $this->actingAs($user)->get(localized_route('quizzes.show', $course));
    $response->assertSee($question->title);
    $response->assertSee('first choice');
    $response->assertSee('second choice');
    $response->assertSee('third choice');

    // when no choice is selected
    $inputData = [
        'questions' => [
            $question->id => [
            ],
        ],
    ];

    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $course))
        ->post(localized_route('quizzes.show-result', $course), $inputData);

    $response->assertSessionHasErrors();

    // when wrong choice is selected
    $inputData = [
        'questions' => [
            $question->id => [
                1,
            ],
        ],
    ];

    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $course))
        ->post(localized_route('quizzes.show-result', $course), $inputData);

    $response->assertSessionHasErrors();

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'score' => 0,
    ]);

    $inputData = [
        'questions' => [
            $question->id => [
                1,
                2,
            ],
        ],
    ];

    $user->refresh();

    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $course))
        ->post(localized_route('quizzes.show-result', $course), $inputData);

    $response->assertRedirect(localized_route('quizzes.show', $course));

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'score' => 1,
    ]);

    $this->assertDatabaseHas('course_user', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
    expect(DB::table('course_user')->where([['course_id', $course->id], ['user_id', $user->id]])->first()->received_certificate_at)->not->toBeNull();
    $this->followRedirects($response)->assertSee(__('You have now completed this course.'));
});

test('when users pass the quiz in first attempt', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $user->courses()->attach(
        $course->id, ['started_at' => now()]
    );
    $quiz = Quiz::factory()->for($course)->create();
    $question = Question::factory()->create(['choices' => ['en' => [['label' => 'first choice', 'value' => 0], ['label' => 'second choice', 'value' => 1], ['label' => 'third choice', 'value' => 2]]]]);
    $quiz->questions()->attach($question);

    $inputData = [
        'questions' => [
            $question->id => [
                1,
                2,
            ],
        ],
    ];

    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $course))
        ->post(localized_route('quizzes.show-result', $course), $inputData);

    $response->assertRedirect(localized_route('quizzes.show', $course));

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'score' => 1,
    ]);

    $this->followRedirects($response)->assertSee(__('You have now completed this course.'));
});

test('when users fail the quiz multiple times', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $user->courses()->attach(
        $course->id, ['started_at' => now()]
    );
    $quiz = Quiz::factory()->for($course)->create();
    $question = Question::factory()->create(['choices' => ['en' => [['label' => 'first choice', 'value' => 0], ['label' => 'second choice', 'value' => 1], ['label' => 'third choice', 'value' => 2]]]]);
    $quiz->questions()->attach($question);

    $inputData = [
        'questions' => [
            $question->id => [
                0,
            ],
        ],
    ];
    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $course))
        ->post(localized_route('quizzes.show-result', $course), $inputData);

    $response->assertSessionHasErrors();

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'score' => 0,
    ]);

    $response = $this->actingAs($user->refresh())
        ->from(localized_route('quizzes.show', $course))
        ->post(localized_route('quizzes.show-result', $course), $inputData);

    $response->assertSessionHasErrors();

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'score' => 0,
    ]);

    $this->followRedirects($response)->assertSee(__('Please try again.'));
});
