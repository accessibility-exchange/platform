<?php

use App\Http\Controllers\ProfileController;

Route::multilingual('/consultants', [ProfileController::class, 'index'])
    ->middleware(['auth'])
    ->name('profiles.index');

Route::multilingual('/consultants/create', [ProfileController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Profile'])
    ->name('profiles.create');

Route::multilingual('/consultants/create', [ProfileController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\Profile'])
    ->name('profiles.store');

Route::multilingual('/consultants/{profile}', [ProfileController::class, 'show'])
    ->middleware(['auth'])
    ->name('profiles.show');

Route::multilingual('/consultants/{profile}/edit', [ProfileController::class, 'edit'])
    ->middleware(['auth', 'can:update,profile'])
    ->name('profiles.edit');

Route::multilingual('/consultants/{profile}/edit', [ProfileController::class, 'update'])
    ->middleware(['auth', 'can:update,profile'])
    ->method('put')
    ->name('profiles.update');

Route::multilingual('/consultants/{profile}/delete', [ProfileController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,profile'])
    ->method('delete')
    ->name('profiles.destroy');
