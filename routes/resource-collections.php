<?php

use App\Http\Controllers\ResourceCollectionController;
use App\Http\Livewire\CollectionResources;
use Illuminate\Support\Facades\Route;

Route::multilingual('/resources', [ResourceCollectionController::class, 'index'])
    ->middleware(['auth'])
    ->name('resource-collections.index');

Route::multilingual('/resources/collections/create', [ResourceCollectionController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\ResourceCollection'])
    ->name('resource-collections.create');

Route::multilingual('/resources/collections/create', [ResourceCollectionController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\ResourceCollection'])
    ->name('resource-collections.store');

Route::multilingual('/resources/collections/{resourceCollection}/edit', [ResourceCollectionController::class, 'edit'])
    ->middleware(['auth', 'can:update,resourceCollection'])
    ->name('resource-collections.edit');

Route::multilingual('/resources/collections/{resourceCollection}/edit', [ResourceCollectionController::class, 'update'])
    ->middleware(['auth', 'can:update,resourceCollection'])
    ->method('put')
    ->name('resource-collections.update');

Route::multilingual('/resources/collections/{resourceCollection}/delete', [ResourceCollectionController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,resourceCollection'])
    ->method('delete')
    ->name('resource-collections.destroy');

Route::multilingual('/resources/collections/{resourceCollection}', [CollectionResources::class, '__invoke'])
    ->middleware(['auth'])
    ->name('resource-collections.show');
