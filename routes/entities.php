<?php

use App\Http\Controllers\EntityController;

Route::multilingual('/entities', [EntityController::class, 'index'])
    ->middleware(['auth'])
    ->name('entities.index');

Route::multilingual('/entities/create', [EntityController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Entity'])
    ->name('entities.create');

Route::multilingual('/entities/create', [EntityController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\Entity'])
    ->name('entities.store');

Route::multilingual('/entities/{entity}', [EntityController::class, 'show'])
    ->middleware(['auth'])
    ->name('entities.show');

Route::multilingual('/entities/{entity}/accessibility-and-inclusion', [EntityController::class, 'show'])
    ->middleware(['auth'])
    ->name('entities.show-accessibility-and-inclusion');

 Route::multilingual('/entities/{entity}/projects', [EntityController::class, 'show'])
    ->middleware(['auth'])
    ->name('entities.show-projects');

Route::multilingual('/entities/{entity}/edit', [EntityController::class, 'edit'])
    ->middleware(['auth', 'can:update,entity'])
    ->name('entities.edit');

Route::multilingual('/entities/{entity}/edit', [EntityController::class, 'update'])
    ->middleware(['auth', 'can:update,entity'])
    ->method('put')
    ->name('entities.update');

Route::multilingual('/entities/{entity}/delete', [EntityController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,entity'])
    ->method('delete')
    ->name('entities.destroy');
