<?php

use App\Livewire\AllLibraries;

Route::prefix('libraries')
    ->name('libraries.')
    ->group(function () {
        Route::multilingual('/', [AllLibraries::class, '__invoke'])
            ->name('index');
    });
