<?php

use App\Http\Controllers\LibraryController;
use App\Livewire\AllLibraries;

Route::multilingual('/libraries', [AllLibraries::class, '__invoke'])
    ->name('libraries.index');

Route::multilingual('/libraries/{library}', [LibraryController::class, '__invoke'])
    ->name('libraries.show');
