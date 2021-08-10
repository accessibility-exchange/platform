<?php

use App\Http\Controllers\InvitationController;

Route::multilingual('/invitations/create', [InvitationController::class, 'create'])
    ->method('post')
    ->name('invitations.create');

Route::get('/invitations/{invitation}', [InvitationController::class, 'accept'])
    ->middleware(['signed'])
    ->name('invitations.accept');

Route::delete('/invitations/{invitation}/cancel', [InvitationController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('invitations.destroy');
