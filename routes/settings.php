<?php

use App\Http\Controllers\SettingsController;

Route::controller(SettingsController::class)
    ->prefix('settings')
    ->name('settings.')
    ->group(function () {
        Route::multilingual('/language-preferences', 'editLanguagePreferences')
            ->middleware(['auth'])
            ->name('edit-language-preferences');

        Route::multilingual('/language-preferences', 'updateLanguagePreferences')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-language-preferences');

        Route::multilingual('/payment-information', 'editPaymentInformation')
            ->middleware(['auth'])
            ->name('edit-payment-information');

        Route::multilingual('/payment-information', 'updatePaymentInformation')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-payment-information');
    });
