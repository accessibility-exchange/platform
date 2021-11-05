<?php

use App\Http\Controllers\ProjectController;

Route::multilingual('/projects/{project}/updates', [ProjectController::class, 'indexProjectUpdates'])
    ->middleware(['auth', 'can:participate,project'])
    ->method('get')
    ->name('projects.index-updates');

Route::multilingual('/projects/{project}/updates/create', [ProjectController::class, 'createProjectUpdate'])
    ->middleware(['auth', 'can:manage,project'])
    ->method('get')
    ->name('projects.create-update');
