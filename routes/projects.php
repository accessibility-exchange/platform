<?php

use App\Http\Controllers\ProjectController;

Route::multilingual('/projects', [ProjectController::class, 'index'])
    ->middleware(['auth'])
    ->name('projects.index');

Route::multilingual('/entities/{entity}/projects', [ProjectController::class, 'entityIndex'])
    ->middleware(['auth'])
    ->name('projects.entity-index');

Route::multilingual('/entities/{entity}/projects/create', [ProjectController::class, 'create'])
    ->middleware(['auth', 'can:createProject,entity'])
    ->name('projects.create');

Route::multilingual('/entities/{entity}/projects/create', [ProjectController::class, 'store'])
    ->method('post')
    ->name('projects.store');

Route::multilingual('/projects/{project}', [ProjectController::class, 'show'])
    ->middleware(['auth'])
    ->name('projects.show');

Route::multilingual('/projects/{project}/edit', [ProjectController::class, 'edit'])
    ->middleware(['auth', 'can:update,project'])
    ->name('projects.edit');

Route::multilingual('/projects/{project}/update', [ProjectController::class, 'update'])
    ->middleware(['auth', 'can:update,project'])
    ->method('put')
    ->name('projects.update');

Route::multilingual('/projects/{project}/change-status', [ProjectController::class, 'updateStatus'])
    ->middleware(['auth', 'can:update,project'])
    ->method('put')
    ->name('projects.update-status');

Route::multilingual('/projects/{project}/delete', [ProjectController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,project'])
    ->method('delete')
    ->name('projects.destroy');

Route::multilingual('/projects/{project}/dashboard', [ProjectController::class, 'manage'])
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.manage');
