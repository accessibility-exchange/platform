<?php

use App\Http\Controllers\MeetingController;

Route::controller(MeetingController::class)
    ->name('meetings.')
    ->group(function () {
        Route::multilingual('/engagements/{engagement}/meetings/create', 'create')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('create');

        Route::multilingual('/engagements/{engagement}/meetings/create', 'store')
            ->method('post')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('store');

        Route::multilingual('/engagements/{engagement}/meetings/{meeting}/edit', 'edit')
            ->middleware(['auth', 'can:update,meeting'])
            ->name('edit');

        Route::multilingual('/engagements/{engagement}/meetings/{meeting}/edit', 'update')
            ->middleware(['auth', 'can:update,meeting'])
            ->method('put')
            ->name('update');

        Route::multilingual('/engagements/{engagement}/meetings/{meeting}/delete', 'destroy')
            ->middleware(['auth', 'can:delete,meeting'])
            ->method('delete')
            ->name('destroy');
    });
