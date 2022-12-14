<?php

use App\Http\Controllers\ResourceCollectionController;
use App\Http\Livewire\CollectionResources;
use Illuminate\Support\Facades\Route;

Route::multilingual('/resources', [ResourceCollectionController::class, 'index'])
    ->middleware(['auth'])
    ->name('resource-collections.index');

Route::multilingual('/resources/collections/{resourceCollection}', [CollectionResources::class, '__invoke'])
    ->middleware(['auth'])
    ->name('resource-collections.show');
