<?php

use App\Livewire\EmailResults;
use App\Mail\QuizResults;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->quiz = Quiz::factory()->for(Course::factory()->create())->create();
    $this->user = User::factory()->create();
});

test('user can send their succeed test results to themselves', function () {
    $user = $this->user;
    actingAs($this->user);
    $emailResults = livewire(EmailResults::class, ['quiz' => $this->quiz]);
    Mail::fake();
    $emailResults->dispatch('send');
    Mail::assertSent(QuizResults::class);
    Mail::assertSent(QuizResults::class, function ($mail) use ($user) {
        return $mail->assertTo($user->email);
    });
});

test('content of the mail should contain course title and user name', function () {
    actingAs($this->user);
    $mail = new QuizResults($this->quiz, $this->user->name);
    $mail->assertSeeInHtml($this->quiz->course->title);
    $mail->assertSeeInHtml($this->user->name, false);
});
