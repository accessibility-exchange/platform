<?php

use App\Livewire\ModuleContent;

Route::multilingual('/courses/{course}/{module}', [ModuleContent::class, '__invoke'])
    ->middleware('auth')
    ->name('modules.module-content');
