<?php

use App\Http\Controllers\AreaTypeController;

Route::controller(AreaTypeController::class)
    ->prefix('area-types')
    ->name('area-types.')
    ->group(function () {
        Route::multilingual('', 'index')
            ->middleware(['auth'])
            ->name('index');
    });
