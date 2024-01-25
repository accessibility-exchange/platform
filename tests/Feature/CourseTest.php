<?php

use App\Models\Course;
use App\Models\Module;
use App\Models\Organization;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('a course can have an author', function () {
    $course = Course::factory()->create();

    $author = 'test author';
    $frAuthor = 'auteur des tests';

    $course->author = $author;
    $course->setTranslation('author', 'fr', $frAuthor);
    $course->save();

    expect($course->author)->toBe($author);
    expect($course->getTranslation('author', 'fr'))->toBe($frAuthor);
});

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

test('a course can belong to an organizations', function () {
    $organization = Organization::factory()->create();
    $course = Course::factory()->for($organization)->create();

    expect($course->organization->id)->toBe($organization->id);
});

test('users can take the quiz for courses once they finish all the modules in the course', function () {
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $module = Module::factory()->for($course)->create();
    $quiz = Quiz::factory()->for($course)->create();
    $question = Question::factory()->create();
    $quiz->questions()->attach($question);

    actingAs($user)->get(localized_route('courses.show', $course))
        ->assertOk()
        ->assertSee(__('not available yet'))
        ->assertDontSee(__('completed'));

    $user->modules()->attach(
        $module->id, [
            'started_content_at' => now(),
        ]
    );

    $user->refresh();

    actingAs($user)->get(localized_route('courses.show', $course))
        ->assertOk()
        ->assertSee(__('not available yet'))
        ->assertSee(__('In progress'));

    $user->modules()->updateExistingPivot(
        $module->id, [
            'finished_content_at' => now(),
        ]
    );

    $user->refresh();

    actingAs($user)->get(localized_route('courses.show', $course))
        ->assertOk()
        ->assertSessionHasNoErrors()
        ->assertDontSee(__('not available yet'))
        ->assertSee(__('completed'));
});

test('users can not take quiz if the course does not have quiz or quiz for the course does not have questions', function () {
    $course = Course::factory()->create();
    $user = User::factory()->create();

    actingAs($user)->get(localized_route('courses.show', $course))
        ->assertOk()
        ->assertDontSee(__('Take Quiz'));

    $module = Module::factory()->for($course)->create();
    $quiz = Quiz::factory()->for($course)->create();

    $user->modules()->attach(
        $module->id, [
            'started_content_at' => now(),
            'finished_content_at' => now(),
        ]
    );

    $user->refresh();

    actingAs($user)->get(localized_route('courses.show', $course))
        ->assertOk()
        ->assertDontSee(__('Take Quiz'));

    $question = Question::factory()->create();
    $quiz->questions()->attach($question);

    actingAs($user)->get(localized_route('courses.show', $course))
        ->assertOk()
        ->assertSee(__('Take Quiz'));
});
