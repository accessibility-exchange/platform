<?php

use App\Http\Controllers\AdminController;
use App\Livewire\AdminEstimatesAndAgreements;
use App\Livewire\ManageAccounts;

Route::controller(AdminController::class)->name('admin.')
    ->group(function () {
        Route::multilingual('/admin/estimates-and-agreements', [AdminEstimatesAndAgreements::class, '__invoke'])
            ->middleware(['auth', 'admin'])
            ->name('estimates-and-agreements');
        Route::multilingual('/admin/accounts', [ManageAccounts::class, '__invoke'])
            ->middleware(['auth', 'admin'])
            ->name('manage-accounts');
    });
