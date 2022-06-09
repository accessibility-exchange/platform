<?php

use App\Http\Controllers\AgeBracketController;

Route::controller(AgeBracketController::class)
    ->prefix('age-brackets')
    ->name('age-brackets.')
    ->group(function () {
        Route::multilingual('', 'index')
            ->middleware(['auth'])
            ->name('index');
    });
