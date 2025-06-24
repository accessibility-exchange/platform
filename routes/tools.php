<?php

use App\Http\Controllers\ToolController;

Route::controller(ToolController::class)
    ->prefix('tools')
    ->name('tools.')
    ->group(function () {
        Route::multilingual('/', 'index')
            ->name('index');

        Route::multilingual('/{tool}', 'show')
            ->name('show');
    });
