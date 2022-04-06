<?php

use App\Http\Controllers\ResourceCollectionController;
use Illuminate\Support\Facades\Route;

Route::multilingual('/resources', [ResourceCollectionController::class, 'index'])
    ->name('resource-collections.index');

Route::multilingual('/resources/collection/{resourceCollection}', [ResourceCollectionController::class, 'show'])
    ->name('resource-collections.show');
