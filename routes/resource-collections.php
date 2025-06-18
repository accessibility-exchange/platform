<?php

use App\Http\Controllers\ResourceCollectionController;
use App\Livewire\CollectionResources;
use Illuminate\Support\Facades\Route;

Route::multilingual('/resources', [ResourceCollectionController::class, 'index'])
    ->name('resource-collections.index');

Route::multilingual('/resources/collections/{resourceCollection}', [CollectionResources::class, '__invoke'])
    ->name('resource-collections.show');
