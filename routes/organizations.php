<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::controller(OrganizationController::class)
    ->name('organizations.')
    ->group(function () {
        Route::multilingual('/organizations', 'index')
            ->middleware(['auth', 'verified', 'can:viewAny,App\Models\Organization'])
            ->name('index');

        Route::multilingual('/organizations/type/select', 'showTypeSelection')
            ->middleware(['auth', 'verified', 'can:create,App\Models\Organization'])
            ->name('show-type-selection');

        Route::multilingual('/organizations/type/store', 'storeType')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:create,App\Models\Organization'])
            ->name('store-type');

        Route::multilingual('/organizations/create', 'create')
            ->middleware(['auth', 'verified', 'can:create,App\Models\Organization'])
            ->name('create');

        Route::multilingual('/organizations/create', 'store')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:create,App\Models\Organization'])
            ->name('store');

        Route::multilingual('/organizations/{organization}/roles/select', 'showRoleSelection')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->name('show-role-selection');

        Route::multilingual('/organizations/{organization}/roles/edit', 'showRoleEdit')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->name('show-role-edit');

        Route::multilingual('/organizations/{organization}/roles/save', 'saveRoles')
            ->method('put')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->name('save-roles');

        Route::multilingual('/organizations/{organization}/languages/select', 'showLanguageSelection')
            ->middleware(['auth', 'can:update,organization'])
            ->name('show-language-selection');

        Route::multilingual('/organizations/{organization}/languages/store', 'storeLanguages')
            ->method('post')
            ->middleware(['auth', 'can:update,organization'])
            ->name('store-languages');

        Route::multilingual('/organizations/{organization}', 'show')
            ->middleware(['auth', 'can:view,organization'])
            ->name('show');

        Route::multilingual('/organizations/{organization}/constituencies', 'show')
            ->middleware(['auth', 'can:view,organization'])
            ->name('show-constituencies');

        Route::multilingual('/organizations/{organization}/interests', 'show')
            ->middleware(['auth', 'can:view,organization'])
            ->name('show-interests');

        Route::multilingual('/organizations/{organization}/projects', 'show')
            ->middleware(['auth', 'can:view,organization'])
            ->name('show-projects');

        Route::multilingual('/organizations/{organization}/contact-information', 'show')
            ->middleware(['auth', 'can:view,organization'])
            ->name('show-contact-information');

        Route::multilingual('/organizations/{organization}/edit', 'edit')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->name('edit');

        Route::multilingual('/organizations/{organization}/edit', 'update')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->method('put')
            ->name('update');

        Route::multilingual('/organizations/{organization}/update-constituencies', 'updateConstituencies')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->method('put')
            ->name('update-constituencies');

        Route::multilingual('/organizations/{organization}/update-interests', 'updateInterests')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->method('put')
            ->name('update-interests');

        Route::multilingual('/organizations/{organization}/update-contact-information', 'updateContactInformation')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->method('put')
            ->name('update-contact-information');

        Route::multilingual('/organizations/{organization}/change-status', 'updatePublicationStatus')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->method('put')
            ->name('update-publication-status');

        Route::multilingual('/organizations/{organization}/delete', 'destroy')
            ->middleware(['auth', 'verified', 'can:delete,organization'])
            ->method('delete')
            ->name('destroy');
    });
