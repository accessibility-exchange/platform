<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserProjectsController;

Route::multilingual('/projects', [UserProjectsController::class, 'show'])
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Project'])
    ->name('projects.my-projects');

Route::multilingual('/projects/contracted', [UserProjectsController::class, 'showContracted'])
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Project'])
    ->name('projects.my-contracted-projects');

Route::multilingual('/projects/participating', [UserProjectsController::class, 'showParticipating'])
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Project'])
    ->name('projects.my-participating-projects');

Route::multilingual('/projects/running', [UserProjectsController::class, 'showRunning'])
    ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Project'])
    ->name('projects.my-running-projects');

Route::controller(ProjectController::class)
    ->prefix('projects')
    ->name('projects')
    ->group(function () {
        Route::multilingual('/all', 'index')
            ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Project'])
            ->name('.index');

        Route::multilingual('/context/select', 'showContextSelection')
            ->middleware(['auth', 'can:create,App\Models\Project'])
            ->name('.show-context-selection');

        Route::multilingual('/create/store-context', 'storeContext')
            ->method('post')
            ->middleware(['auth', 'can:create,App\Models\Project'])
            ->name('.store-context');

        Route::multilingual('/languages/select', 'showLanguageSelection')
            ->middleware(['auth', 'can:create,App\Models\Project'])
            ->name('.show-language-selection');

        Route::multilingual('/create/store-languages', 'storeLanguages')
            ->method('post')
            ->middleware(['auth', 'can:create,App\Models\Project'])
            ->name('.store-languages');

        Route::multilingual('/create', 'create')
            ->middleware(['auth', 'can:create,App\Models\Project'])
            ->name('.create');

        Route::multilingual('/create', 'store')
            ->method('post')
            ->middleware(['auth', 'can:create,App\Models\Project'])
            ->name('.store');

        Route::multilingual('/{project}', 'show')
            ->middleware(['auth', 'verified', 'can:view,project'])
            ->name('.show');

        Route::multilingual('/{project}/team', 'show')
            ->middleware(['auth', 'verified', 'can:view,project'])
            ->name('.show-team');

        Route::multilingual('/{project}/engagements', 'show')
            ->middleware(['auth', 'verified', 'can:view,project'])
            ->name('.show-engagements');

        Route::multilingual('/{project}/outcomes', 'show')
            ->middleware(['auth', 'verified', 'can:view,project'])
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
            ->middleware(['auth', 'can:publish,project', 'can:unpublish,project'])
            ->method('put')
            ->name('.update-publication-status');

        Route::multilingual('/{project}/delete', 'destroy')
            ->middleware(['auth', 'can:delete,project'])
            ->method('delete')
            ->name('.destroy');

        Route::multilingual('/{project}/manage', 'manage')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.manage');

        Route::multilingual('/{project}/estimates-and-agreements/manage', 'manageEstimatesAndAgreements')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.manage-estimates-and-agreements');

        Route::multilingual('/{project}/suggested-steps', 'suggestedSteps')
            ->middleware(['auth', 'can:manage,project'])
            ->name('.suggested-steps');
    });
