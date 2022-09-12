<?php

use App\Http\Controllers\EngagementController;
use App\Http\Livewire\AddEngagementConnector;
use App\Http\Livewire\ManageEngagementConnector;

Route::controller(EngagementController::class)
    ->name('engagements.')
    ->group(function () {
        Route::multilingual('/projects/{project}/engagements/create/languages/select', 'showLanguageSelection')
            ->middleware(['auth', 'can:createEngagement,project'])
            ->name('show-language-selection');

        Route::multilingual('/projects/{project}/engagements/create/store-languages', 'storeLanguages')
            ->method('post')
            ->middleware(['auth', 'can:createEngagement,project'])
            ->name('store-languages');

        Route::multilingual('/projects/{project}/engagements/create', 'create')
            ->middleware(['auth', 'can:createEngagement,project'])
            ->name('create');

        Route::multilingual('/projects/{project}/engagements/create', 'store')
            ->method('post')
            ->middleware(['auth', 'can:createEngagement,project'])
            ->name('store');
    });

Route::controller(EngagementController::class)
    ->prefix('engagements')
    ->name('engagements.')
    ->group(function () {
        Route::multilingual('/{engagement}', 'show')
            ->middleware(['auth', 'verified', 'can:view,engagement'])
            ->name('show');

        Route::multilingual('/{engagement}/format/select', 'showFormatSelection')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('show-format-selection');

        Route::multilingual('/{engagement}/format/store', 'storeFormat')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('store-format');

        Route::multilingual('/{engagement}/recruitment/select', 'showRecruitmentSelection')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('show-recruitment-selection');

        Route::multilingual('/{engagement}/recruitment/store', 'storeRecruitment')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('store-recruitment');

        Route::multilingual('/{engagement}/criteria/select', 'showCriteriaSelection')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('show-criteria-selection');

        Route::multilingual('/{engagement}/edit', 'edit')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('edit');

        Route::multilingual('/{engagement}/update', 'update')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('update');

        Route::multilingual('/{engagement}/languages/edit', 'editLanguages')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('edit-languages');

        Route::multilingual('/{engagement}/languages/update', 'updateLanguages')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('update-languages');

        Route::multilingual('/{engagement}/criteria/edit', 'editCriteria')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('edit-criteria');

        Route::multilingual('/{engagement}/criteria/update', 'updateCriteria')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('update-criteria');

        Route::multilingual('/{engagement}/manage', 'manage')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('manage');

        Route::multilingual('/{engagement}/participants', 'participate')
            ->middleware(['auth', 'can:participate,engagement'])
            ->name('participate');
    });

Route::multilingual('/engagements/{engagement}/connector/manage', [ManageEngagementConnector::class, '__invoke'])
    ->middleware(['auth', 'can:update,engagement'])
    ->name('engagements.manage-connector');

Route::multilingual('/engagements/{engagement}/connector/add', [AddEngagementConnector::class, '__invoke'])
    ->middleware(['auth', 'can:update,engagement'])
    ->name('engagements.add-connector');
