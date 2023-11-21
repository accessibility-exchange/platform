<?php

use App\Http\Controllers\DefinedTermController;
use Illuminate\Support\Facades\Route;

Route::multilingual('/about/glossary', [DefinedTermController::class, 'index'])
    ->name('about.defined-terms.index');
