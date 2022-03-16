<?php

use App\Http\Controllers\DefinedTermController;
use Illuminate\Support\Facades\Route;

Route::multilingual('/about/glossary', [DefinedTermController::class, 'index'])
    ->name('defined-terms.index');
