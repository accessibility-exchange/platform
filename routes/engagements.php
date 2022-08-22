<?php

use App\Http\Controllers\EngagementController;

Route::multilingual('/projects/{project}/engagements/create', [EngagementController::class, 'create'])
    ->middleware(['auth', 'can:createEngagement,project'])
    ->name('engagements.create');

Route::multilingual('/projects/{project}/engagements/create', [EngagementController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:createEngagement,project'])
    ->name('engagements.store');

Route::controller(EngagementController::class)
    ->prefix('engagements')
    ->name('engagements.')
    ->group(function () {
        Route::multilingual('/{engagement}', 'show')
            ->middleware(['auth'])
            ->name('show');

        Route::multilingual('/{engagement}/outreach/select', 'showOutreachSelection')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('show-outreach-selection');

        Route::multilingual('/{engagement}/outreach/store', 'storeOutreach')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('store-outreach');

        Route::multilingual('/{engagement}/recruitment/select', 'showRecruitmentSelection')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('show-recruitment-selection');

        Route::multilingual('/{engagement}/recruitment/store', 'storeRecruitment')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('store-recruitment');

        Route::multilingual('/{engagement}/edit', 'edit')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('edit');

        Route::multilingual('/{engagement}/update', 'update')
            ->middleware(['auth', 'can:update,engagement'])
            ->method('put')
            ->name('update');

        Route::multilingual('/{engagement}/manage', 'manage')
            ->middleware(['auth', 'can:update,engagement'])
            ->name('manage');

        Route::multilingual('/{engagement}/participants', 'participate')
            ->middleware(['auth', 'can:participate,engagement'])
            ->name('participate');
    });
