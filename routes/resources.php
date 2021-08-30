
<?php

use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

Route::multilingual('/resources', [ResourceController::class, 'index'])
    ->name('resources.index');

Route::multilingual('/resources/create', [ResourceController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Resource'])
    ->name('resources.create');

Route::multilingual('/resources/create', [ResourceController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\Resource'])
    ->name('resources.store');

Route::multilingual('/resources/{resource}', [ResourceController::class, 'show'])
    ->name('resources.show');

Route::multilingual('/resources/{resource}/edit', [ResourceController::class, 'edit'])
    ->middleware(['auth', 'can:update,resource'])
    ->name('resources.edit');

Route::multilingual('/resources/{resource}/edit', [ResourceController::class, 'update'])
    ->middleware(['auth', 'can:update,resource'])
    ->method('put')
    ->name('resources.update');

Route::multilingual('/resources/{resource}/delete', [ResourceController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,resource'])
    ->method('delete')
    ->name('resources.destroy');
