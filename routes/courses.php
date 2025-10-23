<?php

use App\Http\Controllers\CourseController;

Route::multilingual('/courses/{course}', [CourseController::class, 'show'])
    ->name('courses.show');
