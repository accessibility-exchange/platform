<?php

use App\Http\Controllers\RegulatedOrganizationController;

Route::controller(RegulatedOrganizationController::class)
    ->name('regulated-organizations.')
    ->group(function () {
        Route::multilingual('/regulated-organizations', 'index')
            ->middleware(['auth', 'verified', 'can:viewAny,App\Models\RegulatedOrganization'])
            ->name('index');

        Route::multilingual('/regulated-organizations/type/select', 'showTypeSelection')
            ->middleware(['auth', 'verified', 'can:create,App\Models\RegulatedOrganization'])
            ->name('show-type-selection');

        Route::multilingual('/regulated-organizations/type/store', 'storeType')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:create,App\Models\RegulatedOrganization'])
            ->name('store-type');

        Route::multilingual('/regulated-organizations/create', 'create')
            ->middleware(['auth', 'verified', 'can:create,App\Models\RegulatedOrganization'])
            ->name('create');

        Route::multilingual('/regulated-organizations/create', 'store')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:create,App\Models\RegulatedOrganization'])
            ->name('store');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}/languages/select', 'showLanguageSelection')
            ->middleware(['auth', 'can:update,regulatedOrganization'])
            ->name('show-language-selection');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}/languages/store', 'storeLanguages')
            ->method('post')
            ->middleware(['auth', 'can:update,regulatedOrganization'])
            ->name('store-languages');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}', 'show')
            ->middleware(['auth', 'can:view,regulatedOrganization'])
            ->name('show');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}/projects', 'show')
            ->middleware(['auth', 'can:view,regulatedOrganization'])
            ->name('show-projects');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}/edit', 'edit')
            ->middleware(['auth', 'can:update,regulatedOrganization'])
            ->name('edit');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}/edit', 'update')
            ->middleware(['auth', 'can:update,regulatedOrganization'])
            ->method('put')
            ->name('update');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}/change-status', 'updatePublicationStatus')
            ->middleware(['auth', 'can:publish,regulatedOrganization'])
            ->method('put')
            ->name('update-publication-status');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}/delete', 'delete')
            ->middleware(['auth', 'can:delete,regulatedOrganization'])
            ->method('get')
            ->name('delete');

        Route::multilingual('/regulated-organizations/{regulatedOrganization}/delete', 'destroy')
            ->middleware(['auth', 'can:delete,regulatedOrganization'])
            ->method('delete')
            ->name('destroy');
    });
