<?php

use App\Http\Controllers\ContractorInvitationController;
use App\Http\Controllers\InvitationController;

Route::controller(InvitationController::class)
    ->prefix('invitations')
    ->name('invitations.')
    ->group(function () {
        Route::multilingual('/create', 'create')
            ->method('post')
            ->name('create');

        Route::get('/{invitation}', 'accept')
            ->middleware(['signed', 'verified'])
            ->name('accept');

        Route::delete('/{invitation}/decline', 'decline')
            ->middleware(['auth', 'verified'])
            ->name('decline');

        Route::delete('/{invitation}/cancel', 'destroy')
            ->middleware(['auth'])
            ->name('destroy');
    });

Route::controller(ContractorInvitationController::class)
    ->prefix('invitations/contractors')
    ->name('contractor-invitations.')
    ->group(function () {
        Route::multilingual('/create', 'create')
            ->method('post')
            ->name('create');

        Route::get('/{invitation}', 'accept')
            ->middleware(['signed', 'verified'])
            ->name('accept');

        Route::delete('/{invitation}/decline', 'decline')
            ->middleware(['auth', 'verified'])
            ->name('decline');

        Route::delete('/{invitation}/cancel', 'destroy')
            ->middleware(['auth'])
            ->name('destroy');
    });
