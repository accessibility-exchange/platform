<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationInvitationController;
use App\Http\Controllers\OrganizationUserController;
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

Route::multilingual('/consultants', [ProfileController::class, 'index'])
    ->middleware(['auth'])
    ->name('profiles.index');

Route::multilingual('/consultants/create', [ProfileController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Profile'])
    ->name('profiles.create');

Route::multilingual('/consultants/create', [ProfileController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\Profile'])
    ->name('profiles.store');

Route::multilingual('/consultants/{profile}', [ProfileController::class, 'show'])
    ->middleware(['auth'])
    ->name('profiles.show');

Route::multilingual('/consultants/{profile}/edit', [ProfileController::class, 'edit'])
    ->middleware(['auth', 'can:update,profile'])
    ->name('profiles.edit');

Route::multilingual('/consultants/{profile}/edit', [ProfileController::class, 'update'])
    ->middleware(['auth', 'can:update,profile'])
    ->method('put')
    ->name('profiles.update');

Route::multilingual('/consultants/{profile}/delete', [ProfileController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,profile'])
    ->method('delete')
    ->name('profiles.destroy');

Route::multilingual('/organizations', [OrganizationController::class, 'index'])
    ->middleware(['auth'])
    ->name('organizations.index');

Route::multilingual('/organizations/create', [OrganizationController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Organization'])
    ->name('organizations.create');

Route::multilingual('/organizations/create', [OrganizationController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\Organization'])
    ->name('organizations.store');

Route::multilingual('/organizations/{organization}', [OrganizationController::class, 'show'])
    ->middleware(['auth'])
    ->name('organizations.show');

Route::multilingual('/organizations/{organization}/edit', [OrganizationController::class, 'edit'])
    ->middleware(['auth', 'can:update,organization'])
    ->name('organizations.edit');

Route::multilingual('/organizations/{organization}/edit', [OrganizationController::class, 'update'])
    ->middleware(['auth', 'can:update,organization'])
    ->method('put')
    ->name('organizations.update');

    Route::multilingual('/organizations/{organization}/members/{user}/edit', [OrganizationUserController::class, 'edit'])
    ->middleware(['auth', 'can:update,organization'])
    ->name('organization-user.edit');

Route::multilingual('/organizations/{organization}/members/{user}/update', [OrganizationUserController::class, 'update'])
    ->middleware(['auth', 'can:update,organization'])
    ->method('put')
    ->name('organization-user.update');

Route::delete('/organizations/{organization}/members/{user}/delete', [OrganizationUserController::class, 'destroy'])
    ->middleware(['auth', 'can:update,organization'])
    ->name('organization-user.destroy');

Route::multilingual('/organizations/{organization}/delete', [OrganizationController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,organization'])
    ->method('delete')
    ->name('organizations.destroy');

Route::multilingual('/organizations/{organization}/members/invite', [OrganizationInvitationController::class, 'create'])
    ->method('post')
    ->name('organization-invitations.create');

Route::get('/invitations/{invitation}', [OrganizationInvitationController::class, 'accept'])
    ->middleware(['signed'])
    ->name('organization-invitations.accept');

Route::delete('/invitations/{invitation}/cancel', [OrganizationInvitationController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('organization-invitations.destroy');

require __DIR__ . '/fortify.php';
