<?php

use App\Http\Controllers\ModuleController;

Route::multilingual('/modules/{module}', [ModuleController::class, 'show'])
    ->name('modules.show');
