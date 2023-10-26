<?php

use App\Http\Controllers\SettingsController;

Route::controller(SettingsController::class)
    ->name('settings.')
    ->group(function () {
        Route::multilingual('/settings', 'settings')
            ->middleware(['auth'])
            ->name('show');

        Route::multilingual('/settings/access-needs', 'editAccessNeeds')
            ->middleware(['auth'])
            ->name('edit-access-needs');

        Route::multilingual('/settings/access-needs', 'updateAccessNeeds')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-access-needs');

        Route::multilingual('/settings/communication-and-consultation-preferences', 'editCommunicationAndConsultationPreferences')
            ->middleware(['auth'])
            ->name('edit-communication-and-consultation-preferences');

        Route::multilingual('/settings/communication-and-consultation-preferences', 'updateCommunicationAndConsultationPreferences')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-communication-and-consultation-preferences');

        Route::multilingual('/settings/language-preferences', 'editLanguagePreferences')
            ->middleware(['auth'])
            ->name('edit-language-preferences');

        Route::multilingual('/settings/language-preferences', 'updateLanguagePreferences')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-language-preferences');

        Route::multilingual('/settings/payment-information', 'editPaymentInformation')
            ->middleware(['auth'])
            ->name('edit-payment-information');

        Route::multilingual('/settings/payment-information', 'updatePaymentInformation')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-payment-information');

        Route::multilingual('/settings/areas-of-interest', 'editAreasOfInterest')
            ->middleware(['auth'])
            ->name('edit-areas-of-interest');

        Route::multilingual('/settings/areas-of-interest', 'updateAreasOfInterest')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-areas-of-interest');

        Route::multilingual('/settings/website-accessibility-preferences', 'editWebsiteAccessibilityPreferences')
            ->middleware(['auth'])
            ->name('edit-website-accessibility-preferences');

        Route::multilingual('/settings/website-accessibility-preferences/sign-language-translations', 'updateWebsiteAccessibilitySignLanguageTranslations')
            ->method('patch')
            ->name('edit-website-accessibility-sign-language-translations');

        Route::multilingual('/settings/website-accessibility-preferences', 'updateWebsiteAccessibilityPreferences')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-website-accessibility-preferences');

        Route::multilingual('/settings/notifications', 'editNotificationPreferences')
            ->middleware(['auth'])
            ->name('edit-notification-preferences');

        Route::multilingual('/settings/notifications', 'updateNotificationPreferences')
            ->method('put')
            ->middleware(['auth'])
            ->name('update-notification-preferences');

        Route::multilingual('/settings/roles-and-permissions', 'editRolesAndPermissions')
            ->middleware(['auth'])
            ->name('edit-roles-and-permissions');

        Route::multilingual('/settings/roles-and-permissions/invite', 'inviteToInvitationable')
            ->middleware(['auth'])
            ->name('invite-to-invitationable');

        Route::multilingual('/settings/account-details', 'editAccountDetails')
            ->middleware(['auth'])
            ->name('edit-account-details');

        Route::multilingual('/settings/delete-account', 'deleteAccount')
            ->middleware(['auth'])
            ->name('delete-account');
    });
