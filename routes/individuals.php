<?php

use App\Http\Controllers\IndividualController;

Route::controller(IndividualController::class)->prefix('individuals')
    ->name('individuals.')
    ->group(function () {
        Route::multilingual('', 'index')
            ->middleware(['auth'])
            ->name('index');

        Route::multilingual('/roles/select', 'showRoleSelection')
            ->middleware(['auth'])
            ->name('show-role-selection');

        Route::multilingual('/roles/edit', 'showRoleEdit')
            ->middleware(['auth'])
            ->name('show-role-edit');

        Route::multilingual('/roles/save', 'saveRoles')
            ->method('put')
            ->middleware(['auth'])
            ->name('save-roles');

        Route::multilingual('/{individual}', 'show')
            ->middleware(['auth', 'can:view,individual'])
            ->name('show');

        Route::multilingual('/{individual}/interests', 'show')
            ->middleware(['auth', 'can:view,individual'])
            ->name('show-interests');

        Route::multilingual('/{individual}/experiences', 'show')
            ->middleware(['auth', 'can:view,individual'])
            ->name('show-experiences');

        Route::multilingual('/{individual}/communication-and-meetings', 'show')
            ->middleware(['auth', 'can:view,individual'])
            ->name('show-communication-and-meeting-preferences');

        Route::multilingual('/{individual}/edit', 'edit')
            ->middleware(['auth', 'can:update,individual'])
            ->name('edit');

        Route::multilingual('/{individual}/edit', 'update')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update');

        Route::multilingual('/{individual}/edit-interests', 'updateInterests')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-interests');

        Route::multilingual('/{individual}/edit-experiences', 'updateExperiences')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-experiences');

        Route::multilingual('/{individual}/edit-communication-and-meeting-preferences', 'updateCommunicationAndMeetingPreferences')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-communication-and-meeting-preferences');

        Route::multilingual('/{individual}/change-status', 'updatePublicationStatus')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-publication-status');

        Route::multilingual('/{individual}/express-interest', 'expressInterest')
            ->method('post')
            ->middleware(['auth', 'can:update,individual'])
            ->name('express-interest');

        Route::multilingual('/{individual}/remove-interest', 'removeInterest')
            ->method('post')
            ->middleware(['auth', 'can:update,individual'])
            ->name('remove-interest');

        Route::multilingual('/{individual}/delete', 'destroy')
            ->middleware(['auth', 'can:delete,individual'])
            ->method('delete')
            ->name('destroy');
    });
