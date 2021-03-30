<?php

use App\Http\Controllers\MembershipController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
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

Route::multilingual('/organizations/{organization}/delete', [OrganizationController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,organization'])
    ->method('delete')
    ->name('organizations.destroy');

Route::multilingual('/entities', [EntityController::class, 'index'])
    ->middleware(['auth'])
    ->name('entities.index');

Route::multilingual('/entities/create', [EntityController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\Entity'])
    ->name('entities.create');

Route::multilingual('/entities/create', [EntityController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\Entity'])
    ->name('entities.store');

Route::multilingual('/entities/{entity}', [EntityController::class, 'show'])
    ->middleware(['auth'])
    ->name('entities.show');

Route::multilingual('/entities/{entity}/edit', [EntityController::class, 'edit'])
    ->middleware(['auth', 'can:update,entity'])
    ->name('entities.edit');

Route::multilingual('/entities/{entity}/edit', [EntityController::class, 'update'])
    ->middleware(['auth', 'can:update,entity'])
    ->method('put')
    ->name('entities.update');

Route::multilingual('/entities/{entity}/delete', [EntityController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,entity'])
    ->method('delete')
    ->name('entities.destroy');

Route::multilingual('/projects', [ProjectController::class, 'index'])
    ->middleware(['auth'])
    ->name('projects.index');

Route::multilingual('/entities/{entity}/projects', [ProjectController::class, 'entityIndex'])
    ->middleware(['auth'])
    ->name('projects.entity-index');

Route::multilingual('/entities/{entity}/projects/create', [ProjectController::class, 'create'])
    ->name('projects.create');

Route::multilingual('/entities/{entity}/projects/create', [ProjectController::class, 'store'])
    ->method('post')
    ->name('projects.store');

Route::multilingual('/projects/{project}', [ProjectController::class, 'show'])
    ->middleware(['auth'])
    ->name('projects.show');

Route::multilingual('/projects/{project}/edit', [ProjectController::class, 'edit'])
    ->name('projects.edit');

Route::multilingual('/projects/{project}/update', [ProjectController::class, 'update'])
    ->method('put')
    ->name('projects.update');

Route::multilingual('/projects/{project}/delete', [ProjectController::class, 'destroy'])
    ->name('projects.destroy');

Route::multilingual('/memberships/{membership}/edit', [MembershipController::class, 'edit'])
    ->name('memberships.edit');

Route::multilingual('/memberships/{membership}/update', [MembershipController::class, 'update'])
    ->method('put')
    ->name('memberships.update');

Route::delete('/memberships/{membership}/delete', [MembershipController::class, 'destroy'])
    ->name('memberships.destroy');


Route::multilingual('/invitations/create', [InvitationController::class, 'create'])
    ->method('post')
    ->name('invitations.create');

Route::get('/invitations/{invitation}', [InvitationController::class, 'accept'])
    ->middleware(['signed'])
    ->name('invitations.accept');

Route::delete('/invitations/{invitation}/cancel', [InvitationController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('invitations.destroy');

require __DIR__ . '/fortify.php';
