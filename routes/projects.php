<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserProjectsController;

Route::multilingual('/projects', [UserProjectsController::class, 'show'])
    ->middleware(['auth'])
    ->name('projects.my-projects');

Route::multilingual('/projects/contracted', [UserProjectsController::class, 'showContracted'])
    ->middleware(['auth'])
    ->name('projects.my-contracted-projects');

Route::multilingual('/projects/participating', [UserProjectsController::class, 'showParticipating'])
    ->middleware(['auth'])
    ->name('projects.my-participating-projects');

Route::multilingual('/projects/running', [UserProjectsController::class, 'showRunning'])
    ->middleware(['auth'])
    ->name('projects.my-running-projects');

Route::controller(ProjectController::class)
    ->prefix('projects')
    ->name('projects')
    ->group(function () {
        Route::multilingual('/all', 'index')
            ->middleware(['auth'])
            ->name('.index');

        Route::multilingual('/create', 'create')
            ->middleware(['auth', 'can:create,App\Models\Project'])
            ->name('.create');

        Route::multilingual('/create/store-context', 'storeContext')
            ->method('post')
            ->middleware(['auth'])
            ->name('.store-context');

        Route::multilingual('/create/store-focus', 'storeFocus')
            ->method('post')
            ->middleware(['auth'])
            ->name('.store-focus');

        Route::multilingual('/create/store-languages', 'storeLanguages')
            ->method('post')
            ->middleware(['auth'])
            ->name('.store-languages');

        Route::multilingual('/create', 'store')
            ->method('post')
            ->name('.store');

        Route::multilingual('/{project}', 'show')
            ->middleware(['auth'])
            ->name('.show');

        Route::multilingual('/{project}/team', 'show')
            ->middleware(['auth'])
            ->name('.show-team');

        Route::multilingual('/{project}/engagements', 'show')
            ->middleware(['auth'])
            ->name('.show-engagements');

        Route::multilingual('/{project}/outcomes', 'show')
            ->middleware(['auth'])
            ->name('.show-outcomes');

        Route::multilingual('/{project}/edit', 'edit')
            ->middleware(['auth', 'can:update,project'])
            ->name('.edit');

        Route::multilingual('/{project}/update', 'update')
            ->middleware(['auth', 'can:update,project'])
            ->method('put')
            ->name('.update');

        Route::multilingual('/{project}/update-team', 'updateTeam')
            ->middleware(['auth', 'can:update,project'])
            ->method('put')
            ->name('.update-team');

        Route::multilingual('/{project}/update-publication-status', 'updatePublicationStatus')
            ->middleware(['auth', 'can:update,project'])
            ->method('put')
            ->name('.update-publication-status');

        Route::multilingual('/{project}/delete', 'destroy')
            ->middleware(['auth', 'can:delete,project'])
            ->method('delete')
            ->name('.destroy');

        Route::multilingual('/{project}/manage', 'manage')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.manage');

        Route::multilingual('/{project}/participate', 'participate')
            ->middleware(['auth', 'can:participate,project'])
            ->name('.participate');

        Route::multilingual('/{project}/update-progress', 'updateProgress')
            ->middleware(['auth', 'can:manage,project'])
            ->method('put')
            ->name('.update-progress');

        Route::multilingual('/{project}/find-participants/interested', 'findInterestedIndividuals')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.find-interested-participants');

        Route::multilingual('/{project}/find-participants/related', 'findRelatedIndividuals')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.find-related-participants');

        Route::multilingual('/{project}/find-participants/all', 'findAllIndividuals')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.find-all-participants');

        Route::multilingual('/{project}/add-participant', 'addParticipant')
            ->method('put')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.add-participant');

        Route::multilingual('/{project}/update-participants', 'updateParticipants')
            ->method('put')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.update-participants');

        Route::multilingual('/{project}/update-participant', 'updateParticipant')
            ->method('put')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.update-participant');

        Route::multilingual('/{project}/remove-participant', 'removeParticipant')
            ->method('put')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.remove-participant');
    });
