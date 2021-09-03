<?php

use App\Http\Controllers\ConsultantController;

Route::multilingual('/consultants', [ConsultantController::class, 'index'])
    ->middleware(['auth'])
    ->name('consultants.index');

Route::multilingual('/consultants/create', [ConsultantController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Consultant'])
    ->name('consultants.create');

Route::multilingual('/consultants/create', [ConsultantController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\Consultant'])
    ->name('consultants.store');

Route::multilingual('/consultants/{consultant}', [ConsultantController::class, 'show'])
    ->middleware(['auth', 'can:view,profile'])
    ->name('consultants.show');

Route::multilingual('/consultants/{consultant}/edit', [ConsultantController::class, 'edit'])
    ->middleware(['auth', 'can:update,profile'])
    ->name('consultants.edit');

Route::multilingual('/consultants/{consultant}/edit', [ConsultantController::class, 'update'])
    ->middleware(['auth', 'can:update,profile'])
    ->method('put')
    ->name('consultants.update');

Route::multilingual('/consultants/{consultant}/change-status', [ConsultantController::class, 'updateStatus'])
    ->middleware(['auth', 'can:update,profile'])
    ->method('put')
    ->name('consultants.update-status');

Route::multilingual('/consultants/{consultant}/delete', [ConsultantController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,profile'])
    ->method('delete')
    ->name('consultants.destroy');
