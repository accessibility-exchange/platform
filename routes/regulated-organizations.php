<?php

use App\Http\Controllers\RegulatedOrganizationController;

Route::controller(RegulatedOrganizationController::class)
    ->prefix('regulated-organizations')
    ->name('regulated-organizations.')
    ->group(function () {
        Route::multilingual('', 'index')
            ->middleware(['auth'])
            ->name('index');

        Route::multilingual('/type/select', 'showTypeSelection')
            ->middleware(['auth', 'verified', 'can:create,App\Models\RegulatedOrganization'])
            ->name('show-type-selection');

        Route::multilingual('/type/store', 'storeType')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:create,App\Models\RegulatedOrganization'])
            ->name('store-type');

        Route::multilingual('/create', 'create')
            ->middleware(['auth', 'verified', 'can:create,App\Models\RegulatedOrganization'])
            ->name('create');

        Route::multilingual('/create', 'store')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:create,App\Models\RegulatedOrganization'])
            ->name('store');

        Route::multilingual('/{regulatedOrganization}/languages/select', 'showLanguageSelection')
            ->middleware(['auth', 'can:update,regulatedOrganization'])
            ->name('show-language-selection');

        Route::multilingual('/{regulatedOrganization}/languages/store', 'storeLanguages')
            ->method('post')
            ->middleware(['auth', 'can:update,regulatedOrganization'])
            ->name('store-languages');

        Route::multilingual('/{regulatedOrganization}', 'show')
            ->middleware(['auth', 'can:view,regulatedOrganization'])
            ->name('show');

        Route::multilingual('/{regulatedOrganization}/projects', 'show')
            ->middleware(['auth', 'can:view,regulatedOrganization'])
            ->name('show-projects');

        Route::multilingual('/{regulatedOrganization}/edit', 'edit')
            ->middleware(['auth', 'can:update,regulatedOrganization'])
            ->name('edit');

        Route::multilingual('/{regulatedOrganization}/edit', 'update')
            ->middleware(['auth', 'can:update,regulatedOrganization'])
            ->method('put')
            ->name('update');

        Route::multilingual('/{regulatedOrganization}/change-status', 'updatePublicationStatus')
            ->middleware(['auth', 'can:publish,regulatedOrganization'])
            ->method('put')
            ->name('update-publication-status');

        Route::multilingual('/{regulatedOrganization}/delete', 'delete')
            ->middleware(['auth', 'can:delete,regulatedOrganization'])
            ->method('get')
            ->name('delete');

        Route::multilingual('/{regulatedOrganization}/delete', 'destroy')
            ->middleware(['auth', 'can:delete,regulatedOrganization'])
            ->method('delete')
            ->name('destroy');
    });
