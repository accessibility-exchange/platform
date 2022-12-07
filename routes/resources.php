
<?php

use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

Route::controller(ResourceController::class)
    ->prefix('resources')
    ->name('resources.')
    ->group(function () {
        Route::multilingual('/resources/all', 'index')
            ->middleware(['auth'])
            ->name('index');

        Route::multilingual('/{resource}', 'show')
            ->middleware(['auth'])
            ->name('show');
    });
