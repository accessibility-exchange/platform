<?php

use App\Http\Controllers\ContractorInvitationController;
use App\Http\Controllers\InvitationController;

Route::controller(InvitationController::class)
    ->name('invitations.')
    ->group(function () {
        Route::multilingual('/invitations/create', 'create')
            ->method('post')
            ->name('create');

        Route::get('/invitations/{invitation}', 'accept')
            ->middleware(['signed', 'verified'])
            ->name('accept');

        Route::delete('/invitations/{invitation}/decline', 'decline')
            ->middleware(['auth', 'verified'])
            ->name('decline');

        Route::delete('/invitations/{invitation}/cancel', 'destroy')
            ->middleware(['auth'])
            ->name('destroy');
    });

Route::controller(ContractorInvitationController::class)
    ->name('contractor-invitations.')
    ->group(function () {
        Route::get('/invitations/contractors/{invitation}', 'accept')
            ->middleware(['signed', 'verified'])
            ->name('accept');

        Route::delete('/invitations/contractors/{invitation}/decline', 'decline')
            ->middleware(['auth', 'verified'])
            ->name('decline');

        Route::delete('/invitations/contractors/{invitation}/cancel', 'destroy')
            ->middleware(['auth'])
            ->name('destroy');
    });
