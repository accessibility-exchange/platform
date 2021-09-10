
<?php

use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Route;

Route::multilingual('/stories', [StoryController::class, 'index'])
    ->name('stories.index');

Route::multilingual('/stories/create', [StoryController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Story'])
    ->name('stories.create');

Route::multilingual('/stories/create', [StoryController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\Story'])
    ->name('stories.store');

Route::multilingual('/stories/{story}', [StoryController::class, 'show'])
    ->name('stories.show');

Route::multilingual('/stories/{story}/edit', [StoryController::class, 'edit'])
    ->middleware(['auth', 'can:update,story'])
    ->name('stories.edit');

Route::multilingual('/stories/{story}/edit', [StoryController::class, 'update'])
    ->middleware(['auth', 'can:update,story'])
    ->method('put')
    ->name('stories.update');

Route::multilingual('/stories/{story}/delete', [StoryController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,story'])
    ->method('delete')
    ->name('stories.destroy');
