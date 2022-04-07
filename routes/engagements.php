<?php

use App\Http\Controllers\EngagementController;

Route::multilingual('/projects/{project}/engagements/create', [EngagementController::class, 'create'])
    ->middleware(['auth', 'can:createEngagement,project'])
    ->name('engagements.create');

Route::multilingual('/projects/{project}/engagements/create', [EngagementController::class, 'store'])
    ->method('post')
    ->name('engagements.store');

Route::multilingual('/projects/{project}/engagements/{engagement}', [EngagementController::class, 'show'])
    ->middleware(['auth'])
    ->name('engagements.show');

Route::multilingual('/projects/{project}/engagements/{engagement}/edit', [EngagementController::class, 'edit'])
    ->middleware(['auth', 'can:update,engagement'])
    ->name('engagements.edit');

Route::multilingual('/projects/{project}/engagements/{engagement}/update', [EngagementController::class, 'update'])
    ->middleware(['auth', 'can:update,engagement'])
    ->method('put')
    ->name('engagements.update');

Route::multilingual('/projects/{project}/engagements/{engagement}/manage', [EngagementController::class, 'manage'])
    ->middleware(['auth', 'can:update,engagement'])
    ->name('engagements.manage');

    Route::multilingual('/projects/{project}/engagements/{engagement}/participants', [EngagementController::class, 'participate'])
    ->middleware(['auth', 'can:participate,engagement'])
    ->name('engagements.participate');
