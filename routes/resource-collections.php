<?php

use App\Livewire\AllCollections;
use App\Livewire\CollectionResources;
use Illuminate\Support\Facades\Route;

Route::prefix('collections')
    ->name('resource-collections.')
    ->group(function () {
        Route::multilingual('/', [AllCollections::class, '__invoke'])
            ->name('index');

        Route::multilingual('/{resourceCollection}', [CollectionResources::class, '__invoke'])
            ->name('show');
    });
