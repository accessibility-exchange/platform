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

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localeViewPath' ]
], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    Route::get('/people', [UserController::class, 'index'])
        ->name('users.index');
    Route::get('/people/{user}', [UserController::class, 'show'])
        ->name('users.show');
    Route::get('/people/{user}/edit', [UserController::class, 'edit'])
        ->middleware('can:update,user')
        ->name('users.edit');
    Route::put('/people/{user}', [UserController::class, 'update'])
        ->middleware('can:update,user')
        ->name('users.update');
});

require __DIR__ . '/auth.php';
