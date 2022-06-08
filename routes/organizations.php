
<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::controller(OrganizationController::class)
    ->prefix('organizations')
    ->name('organizations.')
    ->group(function () {
        Route::multilingual('', 'index')
            ->middleware(['auth'])
            ->name('index');

        Route::multilingual('/type/select', 'showTypeSelection')
            ->middleware(['auth', 'verified', 'can:create,App\Models\Organization'])
            ->name('show-type-selection');

        Route::multilingual('/type/store', 'storeType')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:create,App\Models\Organization'])
            ->name('store-type');

        Route::multilingual('/create', 'create')
            ->middleware(['auth', 'verified', 'can:create,App\Models\Organization'])
            ->name('create');

        Route::multilingual('/create', 'store')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:create,App\Models\Organization'])
            ->name('store');

        Route::multilingual('/{organization}/roles/select', 'showRoleSelection')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->name('show-role-selection');

        Route::multilingual('/{organization}/roles/store', 'storeRoles')
            ->method('post')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->name('store-roles');

        Route::multilingual('/{organization}/languages/select', 'showLanguageSelection')
            ->middleware(['auth', 'can:update,organization'])
            ->name('show-language-selection');

        Route::multilingual('/{organization}/languages/store', 'storeLanguages')
            ->method('post')
            ->middleware(['auth', 'can:update,organization'])
            ->name('store-languages');

        Route::multilingual('/{organization}', 'show')
            ->middleware(['auth', 'can:view,organization'])
            ->name('show');

        Route::multilingual('/{organization}/edit', 'edit')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->name('edit');

        Route::multilingual('/{organization}/edit', 'update')
            ->middleware(['auth', 'verified', 'can:update,organization'])
            ->method('put')
            ->name('update');

        Route::multilingual('/{organization}/delete', 'destroy')
            ->middleware(['auth', 'verified', 'can:delete,organization'])
            ->method('delete')
            ->name('destroy');
    });
