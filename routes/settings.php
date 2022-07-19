<?php

use App\Http\Controllers\SettingsController;

Route::controller(SettingsController::class)
    ->prefix('settings')
    ->name('settings.')
    ->group(function () {
        Route::multilingual('', 'settings')
            ->middleware(['auth'])
            ->name('show');

        Route::multilingual('/access-needs', 'editAccessNeeds')
            ->middleware(['auth'])
            ->name('edit-access-needs');

        Route::multilingual('/access-needs', 'updateAccessNeeds')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-access-needs');

        Route::multilingual('/communication-and-consultation-preferences', 'editCommunicationAndConsultationPreferences')
            ->middleware(['auth'])
            ->name('edit-communication-and-consultation-preferences');

        Route::multilingual('/communication-and-consultation-preferences', 'updateCommunicationAndConsultationPreferences')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-communication-and-consultation-preferences');

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

        Route::multilingual('/areas-of-interest', 'editAreasOfInterest')
            ->middleware(['auth'])
            ->name('edit-areas-of-interest');

        Route::multilingual('/areas-of-interest', 'updateAreasOfInterest')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-areas-of-interest');
    });
