<?php

use App\Http\Controllers\AdminController;
use App\Livewire\AdminEstimatesAndAgreements;
use App\Livewire\ManageAccounts;

Route::controller(AdminController::class)
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::multilingual('/estimates-and-agreements', [AdminEstimatesAndAgreements::class, '__invoke'])
            ->middleware(['auth', 'admin'])
            ->name('estimates-and-agreements');
        Route::multilingual('/accounts', [ManageAccounts::class, '__invoke'])
            ->middleware(['auth', 'admin'])
            ->name('manage-accounts');
    });
