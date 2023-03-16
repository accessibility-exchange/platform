<?php

use App\Http\Controllers\ResourceController;
use App\Http\Livewire\AllResources;
use Illuminate\Support\Facades\Route;

Route::controller(ResourceController::class)
    ->prefix('resources')
    ->name('resources.')
    ->group(function () {
        Route::multilingual('/all', [AllResources::class, '__invoke'])
            ->middleware(['auth'])
            ->name('index');

        Route::multilingual('/{resource}', 'show')
            ->middleware(['auth'])
            ->name('show');
    });
