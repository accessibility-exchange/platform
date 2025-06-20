<?php

use App\Livewire\AllLibraries;

Route::prefix('collections')
    ->name('resource-collections.')
    ->group(function () {
        Route::multilingual('/', [AllLibraries::class, '__invoke'])
            ->name('index');
    });
