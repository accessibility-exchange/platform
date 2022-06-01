<?php

use App\Http\Controllers\BlockListController;

Route::controller(BlockListController::class)
    ->name('block-list.')
    ->group(function () {
        Route::multilingual('/settings/block-list', 'show')
            ->middleware(['auth', 'can:block'])
            ->name('show');

        Route::multilingual('/settings/block-list/block', 'block')
            ->method('post')
            ->middleware(['auth', 'can:block'])
            ->name('block');

        Route::multilingual('/settings/block-list/unblock', 'unblock')
            ->method('post')
            ->middleware(['auth', 'can:block'])
            ->name('unblock');
    });
