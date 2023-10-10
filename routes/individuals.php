<?php

use App\Http\Controllers\IndividualController;

Route::controller(IndividualController::class)->name('individuals.')
    ->group(function () {
        Route::multilingual('/individuals', 'index')
            ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Individual'])
            ->name('index');

        Route::multilingual('/individuals/roles/select', 'showRoleSelection')
            ->middleware(['auth'])
            ->name('show-role-selection');

        Route::multilingual('/individuals/roles/edit', 'showRoleEdit')
            ->middleware(['auth'])
            ->name('show-role-edit');

        Route::multilingual('/individuals/roles/save', 'saveRoles')
            ->method('put')
            ->middleware(['auth'])
            ->name('save-roles');

        Route::multilingual('/individuals/{individual}', 'show')
            ->middleware(['auth', 'verified', 'can:view,individual'])
            ->name('show');

        Route::multilingual('/individuals/{individual}/interests', 'show')
            ->middleware(['auth', 'verified', 'can:view,individual'])
            ->name('show-interests');

        Route::multilingual('/individuals/{individual}/experiences', 'show')
            ->middleware(['auth', 'verified', 'can:view,individual'])
            ->name('show-experiences');

        Route::multilingual('/individuals/{individual}/communication-and-meetings', 'show')
            ->middleware(['auth', 'verified', 'can:view,individual'])
            ->name('show-communication-and-consultation-preferences');

        Route::multilingual('/individuals/{individual}/edit', 'edit')
            ->middleware(['auth', 'can:update,individual'])
            ->name('edit');

        Route::multilingual('/individuals/{individual}/edit', 'update')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update');

        Route::multilingual('/individuals/{individual}/edit-constituencies', 'updateConstituencies')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-constituencies');

        Route::multilingual('/individuals/{individual}/edit-interests', 'updateInterests')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-interests');

        Route::multilingual('/individuals/{individual}/edit-experiences', 'updateExperiences')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-experiences');

        Route::multilingual('/individuals/{individual}/edit-communication-and-consultation-preferences', 'updateCommunicationAndConsultationPreferences')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-communication-and-consultation-preferences');

        Route::multilingual('/individuals/{individual}/change-status', 'updatePublicationStatus')
            ->middleware(['auth', 'can:update,individual'])
            ->method('put')
            ->name('update-publication-status');

        Route::multilingual('/individuals/{individual}/delete', 'destroy')
            ->middleware(['auth', 'can:delete,individual'])
            ->method('delete')
            ->name('destroy');
    });
