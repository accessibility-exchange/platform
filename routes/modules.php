<?php

use App\Http\Livewire\ModuleContent;

Route::multilingual('/modules/{module}', [ModuleContent::class, '__invoke'])
    ->name('modules.module-content');
