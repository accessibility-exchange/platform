<?php

use App\Http\Controllers\CourseController;

Route::multilingual('/courses', [CourseController::class, 'index'])
    ->name('courses.index');

Route::multilingual('/courses/{course}', [CourseController::class, 'show'])
    ->middleware('auth')
    ->name('courses.show');
