<?php

use App\Http\Livewire\EmailResults;
use App\Mail\QuizResults;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use function Pest\Livewire\livewire;

test('user can send their succeed test results to themselves', function () {
    $quiz = Quiz::factory()->for(Course::factory()->create())->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $emailResults = livewire(EmailResults::class, ['quiz' => $quiz]);
    Mail::fake();
    $emailResults->emit('send');
    Mail::assertSent(QuizResults::class);
    Mail::assertSent(QuizResults::class, function ($mail) use ($user) {
        return $mail->assertTo($user->email);
    });
});

test('content of the mail should contain course title and user name', function () {
    $quiz = Quiz::factory()->for(Course::factory()->create())->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $mail = new QuizResults($quiz, $user->name);
    $mail->assertSeeInHtml($quiz->course->title);
    $mail->assertSeeInHtml($user->name);
});
