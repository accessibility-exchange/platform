<?php

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

Route::multilingual('/people', [UserController::class, 'index'])
    ->name('users.index');
Route::multilingual('/people/{user}', [UserController::class, 'show'])
    ->name('users.show');
Route::multilingual('/people/{user}/edit', [UserController::class, 'edit'])
    ->middleware(['can:update,user'])
    ->name('users.edit');
Route::multilingual('/people/{user}', [UserController::class, 'update'])
    ->method('put')
    ->middleware('can:update,user')
    ->name('users.update');

require __DIR__ . '/auth.php';
