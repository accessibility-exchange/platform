<?php

use App\Http\Controllers\ResourceController;
use App\Http\Livewire\AllResources;
use Illuminate\Support\Facades\Route;

Route::controller(ResourceController::class)
    ->name('resources.')
    ->group(function () {
        Route::multilingual('/resources/all', [AllResources::class, '__invoke'])
            ->middleware(['auth'])
            ->name('index');

        Route::multilingual('/resources/{resource}', 'show')
            ->middleware(['auth'])
            ->name('show');
    });
