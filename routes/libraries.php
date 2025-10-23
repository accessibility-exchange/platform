<?php

use App\Livewire\AllLibraries;
use App\Livewire\LibraryResources;

Route::prefix('libraries')
    ->name('libraries.')
    ->group(function () {
        Route::multilingual('/', [AllLibraries::class, '__invoke'])
            ->name('index');

        Route::multilingual('/{library}', [LibraryResources::class, '__invoke'])
            ->name('show');
    });
