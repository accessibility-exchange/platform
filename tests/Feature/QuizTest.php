<?php

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
    $response->assertOk();
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
        'attempts' => 1,
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

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'attempts' => 2,
        'score' => 1,
    ]);

    $this->assertDatabaseHas('course_user', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
    $this->assertNotNull(DB::table('course_user')->where([['course_id', $course->id], ['user_id', $user->id]])->first()->received_certificate_at);
    $response->assertSee(__('Congratulations! You have passed the quiz.'));
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

    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'attempts' => 1,
        'score' => 1,
    ]);
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
        'attempts' => 1,
        'score' => 0,
    ]);

    $response = $this->actingAs($user->refresh())
        ->from(localized_route('quizzes.show', $course))
        ->post(localized_route('quizzes.show-result', $course), $inputData);

    $response->assertSessionHasErrors();

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'attempts' => 2,
        'score' => 0,
    ]);
});
