<?php

use App\Http\Livewire\ModuleContent;

Route::multilingual('/modules/{module}', [ModuleContent::class, '__invoke'])
    ->middleware('auth')
    ->name('modules.module-content');
