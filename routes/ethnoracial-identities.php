<?php

use App\Http\Controllers\EthnoracialIdentityController;

Route::controller(EthnoracialIdentityController::class)
    ->prefix('ethnoracial-identities')
    ->name('ethnoracial-identities.')
    ->group(function () {
        Route::multilingual('', 'index')
            ->middleware(['auth'])
            ->name('index');
    });
