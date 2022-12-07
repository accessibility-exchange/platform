<?php

use App\Http\Livewire\EmailResults;
use App\Mail\QuizResults;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('user can send their succeed test results to themselves', function () {
    $quiz = Quiz::factory()->for(Course::factory()->create())->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $emailResults = $this->livewire(EmailResults::class, ['quiz' => $quiz, 'results' => []]);
    Mail::fake();
    $emailResults->emit('send');
    Mail::assertSent(QuizResults::class);
    Mail::assertSent(QuizResults::class, function ($mail) use ($user) {
        return $mail->assertTo($user->email);
    });
});
