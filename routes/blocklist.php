<?php

use App\Http\Controllers\BlocklistController;

Route::controller(BlocklistController::class)
    ->name('blocklist.')
    ->group(function () {
        Route::multilingual('/settings/blocklist', 'show')
            ->middleware(['auth', 'can:block'])
            ->name('show');

        Route::multilingual('/settings/blocklist/block', 'block')
            ->method('post')
            ->middleware(['auth', 'can:block'])
            ->name('block');

        Route::multilingual('/settings/blocklist/unblock', 'unblock')
            ->method('post')
            ->middleware(['auth', 'can:block'])
            ->name('unblock');
    });
