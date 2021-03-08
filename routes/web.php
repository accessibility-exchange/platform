<?php

use App\Http\Controllers\ConsultantProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', locale());
Route::multilingual('/', function () {
    return view('welcome');
})->name('welcome');

Route::multilingual('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified:' . locale() . '.verification.notice'])->name('dashboard');

Route::multilingual('/account/edit', [UserController::class, 'edit'])
    ->middleware(['auth'])
    ->name('users.edit');

Route::multilingual('/account/admin', [UserController::class, 'admin'])
    ->middleware(['auth'])
    ->name('users.admin');

Route::multilingual('/account/delete', [UserController::class, 'destroy'])
    ->method('delete')
    ->middleware(['auth'])
    ->name('users.destroy');

Route::multilingual('/consultants', [ConsultantProfileController::class, 'index'])
    ->middleware(['auth'])
    ->name('consultant-profiles.index');

Route::multilingual('/consultants/create', [ConsultantProfileController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\ConsultantProfile'])
    ->name('consultant-profiles.create');

Route::multilingual('/consultants/create', [ConsultantProfileController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\ConsultantProfile'])
    ->name('consultant-profiles.store');

Route::multilingual('/consultants/{consultantProfile}', [ConsultantProfileController::class, 'show'])
    ->name('consultant-profiles.show');

Route::multilingual('/consultants/{consultantProfile}/edit', [ConsultantProfileController::class, 'edit'])
    ->middleware(['auth', 'can:update,consultantProfile'])
    ->name('consultant-profiles.edit');

Route::multilingual('/consultants/{consultantProfile}/edit', [ConsultantProfileController::class, 'update'])
    ->middleware(['auth', 'can:update,consultantProfile'])
    ->method('put')
    ->name('consultant-profiles.update');

    Route::multilingual('/consultants/{consultantProfile}/delete', [ConsultantProfileController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,consultantProfile'])
    ->method('delete')
    ->name('consultant-profiles.destroy');

require __DIR__ . '/fortify.php';
