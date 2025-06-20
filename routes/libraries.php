<?php

use App\Livewire\AllLibraries;

Route::multilingual('/libraries', [AllLibraries::class, '__invoke'])
    ->name('libraries.index');
