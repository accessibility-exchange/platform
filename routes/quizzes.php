<?php

use App\Http\Controllers\QuizController;

Route::controller(QuizController::class)
    ->name('quizzes')
    ->group(function () {
        Route::multilingual('/quizzes/courses/{course}/quiz', 'show')
            ->middleware('auth')
            ->name('.show');

        Route::multilingual('/quizzes/courses/{course}/quiz/result', 'storeQuizResult')
            ->method('post')
            ->middleware('auth')
            ->name('.show-result');
    });
