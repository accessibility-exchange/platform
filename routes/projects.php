<?php

use App\Http\Controllers\ProjectController;

Route::multilingual('/projects', [ProjectController::class, 'index'])
    ->middleware(['auth'])
    ->name('projects.index');

Route::multilingual('/projects/create', [ProjectController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Project'])
    ->name('projects.create');

Route::multilingual('/projects/create/store-context', [ProjectController::class, 'storeContext'])
    ->method('post')
    ->middleware(['auth'])
    ->name('projects.store-context');

Route::multilingual('/projects/create/store-focus', [ProjectController::class, 'storeFocus'])
    ->method('post')
    ->middleware(['auth'])
    ->name('projects.store-focus');

Route::multilingual('/projects/create/store-languages', [ProjectController::class, 'storeLanguages'])
    ->method('post')
    ->middleware(['auth'])
    ->name('projects.store-languages');

Route::multilingual('/projects/create', [ProjectController::class, 'store'])
    ->method('post')
    ->name('projects.store');

Route::multilingual('/projects/{project}', [ProjectController::class, 'show'])
    ->middleware(['auth'])
    ->name('projects.show');

Route::multilingual('/projects/{project}/team', [ProjectController::class, 'show'])
    ->middleware(['auth'])
    ->name('projects.show-team');

Route::multilingual('/projects/{project}/engagements', [ProjectController::class, 'show'])
    ->middleware(['auth'])
    ->name('projects.show-engagements');

Route::multilingual('/projects/{project}/outcomes', [ProjectController::class, 'show'])
    ->middleware(['auth'])
    ->name('projects.show-outcomes');

Route::multilingual('/projects/{project}/edit', [ProjectController::class, 'edit'])
    ->middleware(['auth', 'can:update,project'])
    ->name('projects.edit');

Route::multilingual('/projects/{project}/update', [ProjectController::class, 'update'])
    ->middleware(['auth', 'can:update,project'])
    ->method('put')
    ->name('projects.update');

Route::multilingual('/projects/{project}/update-team', [ProjectController::class, 'updateTeam'])
    ->middleware(['auth', 'can:update,project'])
    ->method('put')
    ->name('projects.update-team');

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

Route::multilingual('/projects/{project}/find-participants/interested', [ProjectController::class, 'findInterestedIndividuals'])
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.find-interested-participants');

Route::multilingual('/projects/{project}/find-participants/related', [ProjectController::class, 'findRelatedIndividuals'])
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.find-related-participants');

Route::multilingual('/projects/{project}/find-participants/all', [ProjectController::class, 'findAllIndividuals'])
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.find-all-participants');

Route::multilingual('/projects/{project}/add-participant', [ProjectController::class, 'addParticipant'])
    ->method('put')
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.add-participant');

Route::multilingual('/projects/{project}/update-participants', [ProjectController::class, 'updateParticipants'])
    ->method('put')
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.update-participants');

Route::multilingual('/projects/{project}/update-participant', [ProjectController::class, 'updateParticipant'])
    ->method('put')
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.update-participant');

Route::multilingual('/projects/{project}/remove-participant', [ProjectController::class, 'removeParticipant'])
    ->method('put')
    ->middleware(['auth', 'can:manage,project'])
    ->name('projects.remove-participant');
