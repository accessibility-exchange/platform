<?php

use App\Livewire\AllLibraries;
use App\Livewire\ShowLibrary;

Route::prefix('libraries')
    ->name('libraries.')
    ->group(function () {
        Route::multilingual('/', [AllLibraries::class, '__invoke'])
            ->name('index');

        Route::multilingual('/{library}', [ShowLibrary::class, '__invoke'])
            ->name('show');
    });
