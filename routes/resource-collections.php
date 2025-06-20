<?php

use App\Livewire\AllCollections;
use App\Livewire\CollectionResources;
use Illuminate\Support\Facades\Route;

Route::multilingual('/resources/collections', [AllCollections::class, '__invoke'])
    ->name('resource-collections.index');

Route::multilingual('/resources/collections/{resourceCollection}', [CollectionResources::class, '__invoke'])
    ->name('resource-collections.show');
