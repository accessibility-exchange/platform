<?php

use App\Http\Controllers\ResourcesAndTrainingController;
use Illuminate\Support\Facades\Route;

Route::multilingual('/resources-and-training', ResourcesAndTrainingController::class)
    ->name('resources-and-training');
