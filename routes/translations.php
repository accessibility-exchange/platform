<?php

use App\Http\Controllers\TranslationController;

Route::multilingual('/translations/add', [TranslationController::class, 'add'])
    ->middleware(['auth'])
    ->method('put')
    ->name('translations.add');

Route::multilingual('/translations/delete', [TranslationController::class, 'destroy'])
    ->middleware(['auth'])
    ->method('put')
    ->name('translations.destroy');
