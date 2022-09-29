<?php

use App\Http\Controllers\MeetingController;

Route::controller(MeetingController::class)
    ->prefix('/engagements/{engagement}/meetings')
    ->name('meetings.')
    ->group(function () {
        Route::multilingual('/create', 'create')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('create');

        Route::multilingual('/create', 'store')
            ->method('post')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('store');

        Route::multilingual('/{meeting}/edit', 'edit')
            ->middleware(['auth', 'can:update,meeting'])
            ->name('edit');

        Route::multilingual('/{meeting}/edit', 'update')
            ->middleware(['auth', 'can:update,meeting'])
            ->method('put')
            ->name('update');

        Route::multilingual('/{meeting}/delete', 'destroy')
            ->middleware(['auth', 'can:delete,meeting'])
            ->method('delete')
            ->name('destroy');
    });
