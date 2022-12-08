<?php

use App\Http\Controllers\QuizController;

Route::multilingual('/quizzes/{quiz}', [QuizController::class, 'show'])
    ->middleware('auth')
    ->name('quizzes.show');

Route::multilingual('/quizzes/{quiz}/result', [QuizController::class, 'storeQuizResult'])
    ->method('post')
    ->middleware('auth')
    ->name('quizzes.show-result');
