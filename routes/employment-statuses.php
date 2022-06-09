<?php

use App\Http\Controllers\EmploymentStatusController;

Route::controller(EmploymentStatusController::class)
    ->prefix('employment-statuses')
    ->name('employment-statuses.')
    ->group(function () {
        Route::multilingual('', 'index')
            ->middleware(['auth'])
            ->name('index');
    });
