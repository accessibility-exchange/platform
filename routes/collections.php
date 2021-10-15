<?php

use App\Http\Controllers\CollectionController;
use Illuminate\Support\Facades\Route;

Route::multilingual('/resources', [CollectionController::class, 'index'])
    ->name('collections.index');

Route::multilingual('/resources/collections/{collection}', [CollectionController::class, 'show'])
    ->name('collections.show');
