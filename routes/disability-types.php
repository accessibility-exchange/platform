<?php

use App\Http\Controllers\DisabilityTypeController;

Route::controller(DisabilityTypeController::class)
    ->prefix('disability-types')
    ->name('disability-types.')
    ->group(function () {
        Route::multilingual('', 'index')
            ->middleware(['auth'])
            ->name('index');
    });
