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

Route::multilingual('/projects/{project}/update-publication-status', [ProjectController::class, 'updatePublicationStatus'])
    ->middleware(['auth', 'can:update,project'])
    ->method('put')
    ->name('projects.update-publication-status');

Route::multilingual('/projects/{project}/delete', [ProjectController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,project'])
    ->method('delete')
    ->name('projects.destroy');

Route::multilingual('/projects/{project}/manage', [ProjectController::class, 'manage'])
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.manage');

Route::multilingual('/projects/{project}/participate', [ProjectController::class, 'participate'])
    ->middleware(['auth', 'can:participate,project'])
    ->name('projects.participate');

Route::multilingual('/projects/{project}/update-progress', [ProjectController::class, 'updateProgress'])
    ->middleware(['auth', 'can:manage,project'])
    ->method('put')
    ->name('projects.update-progress');

Route::multilingual('/projects/{project}/find-consultants/interested', [ProjectController::class, 'findInterestedConsultants'])
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.find-interested-consultants');

Route::multilingual('/projects/{project}/find-consultants/related', [ProjectController::class, 'findRelatedConsultants'])
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.find-related-consultants');

Route::multilingual('/projects/{project}/find-consultants/all', [ProjectController::class, 'findAllConsultants'])
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.find-all-consultants');

Route::multilingual('/projects/{project}/add-consultant', [ProjectController::class, 'addConsultant'])
    ->method('put')
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.add-consultant');

Route::multilingual('/projects/{project}/update-consultants', [ProjectController::class, 'updateConsultants'])
    ->method('put')
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.update-consultants');

Route::multilingual('/projects/{project}/update-consultant', [ProjectController::class, 'updateConsultant'])
    ->method('put')
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.update-consultant');

Route::multilingual('/projects/{project}/remove-consultant', [ProjectController::class, 'removeConsultant'])
    ->method('put')
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.remove-consultant');
