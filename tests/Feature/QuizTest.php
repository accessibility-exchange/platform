<?php

use App\Models\Choice;
use App\Models\Course;
use App\Models\Module;
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

test('users can view quiz results on finishing it', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $user->courses()->attach(
        $course->id, ['started_at' => now(), 'finished_at' => now()]
    );
    $quiz = Quiz::factory()->for($course)->create();
    $firstQuestion = Question::factory()->for($quiz)->create(['minimum_choices' => 2]);
    $firstQuestionCorrectChoice = Choice::factory()->for($firstQuestion)->create(['is_answer' => true]);
    $firstQuestionWrongChoice = Choice::factory()->for($firstQuestion)->create();

    $secondQuestion = Question::factory()->for($quiz)->create();
    $secondQuestionCorrectChoice = Choice::factory()->for($secondQuestion)->create(['is_answer' => true]);
    $secondQuestionWrongChoice = Choice::factory()->for($secondQuestion)->create();

    $response = $this->actingAs($user)->get(localized_route('quizzes.show', $quiz));
    $response->assertOk();
    $response->assertSee($firstQuestion->title);
    $response->assertSee($firstQuestionCorrectChoice->label);
    $response->assertSee($firstQuestionWrongChoice->label);
    $response->assertSee($secondQuestion->title);
    $response->assertSee($secondQuestionCorrectChoice->label);
    $response->assertSee($secondQuestionWrongChoice->label);

    $inputData = [
        'question_'.$firstQuestion->id => [$firstQuestionCorrectChoice->value],
    ];

    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $quiz))
        ->post(localized_route('quizzes.show-result', $quiz), $inputData);

    $response->assertSessionHasErrors(['question_'.$secondQuestion->id => 'You must enter your question '.$secondQuestion->id.'.']);

    $inputData = [
        'question_'.$firstQuestion->id => [$firstQuestionCorrectChoice->id],
        'question_'.$secondQuestion->id => [$secondQuestionCorrectChoice->id],
    ];

    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $quiz))
        ->post(localized_route('quizzes.show-result', $quiz), $inputData);

    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'attempts' => 1,
        'score' => 0.5,
    ]);
    $response->assertSee(__('You have not passed the quiz.'));

    $firstQuestionAnotherCorrectChoice = Choice::factory()->for($firstQuestion)->create(['is_answer' => true]);

    $inputData = [
        'question_'.$firstQuestion->id => [$firstQuestionCorrectChoice->id, $firstQuestionAnotherCorrectChoice->id],
        'question_'.$secondQuestion->id => [$secondQuestionCorrectChoice->id],
    ];

    $user->refresh();

    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $quiz))
        ->post(localized_route('quizzes.show-result', $quiz), $inputData);

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
        $course->id, ['started_at' => now(), 'finished_at' => now()]
    );
    $quiz = Quiz::factory()->for($course)->create();
    $firstQuestion = Question::factory()->for($quiz)->create();
    $firstQuestionCorrectChoice = Choice::factory()->for($firstQuestion)->create(['is_answer' => true]);

    $inputData = [
        'question_'.$firstQuestion->id => [$firstQuestionCorrectChoice->id],
    ];
    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $quiz))
        ->post(localized_route('quizzes.show-result', $quiz), $inputData);

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
        $course->id, ['started_at' => now(), 'finished_at' => now()]
    );
    $quiz = Quiz::factory()->for($course)->create();
    $firstQuestion = Question::factory()->for($quiz)->create();
    $firstQuestionWrongChoice = Choice::factory()->for($firstQuestion)->create();

    $inputData = [
        'question_'.$firstQuestion->id => [$firstQuestionWrongChoice->id],
    ];
    $response = $this->actingAs($user)
        ->from(localized_route('quizzes.show', $quiz))
        ->post(localized_route('quizzes.show-result', $quiz), $inputData);

    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'attempts' => 1,
        'score' => 0,
    ]);

    $response = $this->actingAs($user->refresh())
        ->from(localized_route('quizzes.show', $quiz))
        ->post(localized_route('quizzes.show-result', $quiz), $inputData);

    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('quiz_user', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'attempts' => 2,
        'score' => 0,
    ]);
});
