<?php

use App\Http\Controllers\QuizController;

Route::controller(QuizController::class)
    ->prefix('quizzes')
    ->name('quizzes')
    ->group(function () {
        Route::multilingual('courses/{course}/quiz', 'show')
            ->middleware('auth')
            ->name('.show');

        Route::multilingual('/courses/{course}/quiz/result', 'storeQuizResult')
            ->method('post')
            ->middleware('auth')
            ->name('.store-result');
    });
