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

Route::redirect('/', \locale());
Route::multilingual('/', function () {
    return view('welcome');
})->name('welcome');

Route::multilingual('/dashboard', [UserController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::multilingual('/settings', [UserController::class, 'settings'])
    ->middleware(['auth'])
    ->name('users.settings');

Route::multilingual('/settings/basic-information', [UserController::class, 'edit'])
    ->middleware(['auth'])
    ->name('users.edit');

Route::multilingual('/settings/roles-and-permissions', [UserController::class, 'editRolesAndPermissions'])
    ->middleware(['auth'])
    ->name('users.edit_roles_and_permissions');

Route::multilingual('/settings/display-preferences', [UserController::class, 'editDisplayPreferences'])
    ->middleware(['auth'])
    ->name('users.edit_display_preferences');

Route::multilingual('/settings/display-preferences', [UserController::class, 'updateDisplayPreferences'])
    ->method('put')
    ->middleware(['auth'])
    ->name('users.update_display_preferences');

Route::multilingual('/settings/notifications', [UserController::class, 'editNotificationPreferences'])
    ->middleware(['auth'])
    ->name('users.edit_notification_preferences');

Route::multilingual('/settings/change-password', [UserController::class, 'admin'])
    ->middleware(['auth'])
    ->name('users.admin');

Route::multilingual('/my-projects', [UserController::class, 'showMyProjects'])
    ->middleware(['auth'])
    ->name('users.show_my_projects');

Route::multilingual('/settings/delete-account', [UserController::class, 'delete'])
    ->middleware(['auth'])
    ->name('users.delete');

Route::multilingual('/account/delete', [UserController::class, 'destroy'])
    ->method('delete')
    ->middleware(['auth'])
    ->name('users.destroy');

require __DIR__ . '/community-members.php';
require __DIR__ . '/organizations.php';
require __DIR__ . '/entities.php';
require __DIR__ . '/projects.php';
require __DIR__ . '/resources.php';
require __DIR__ . '/collections.php';
require __DIR__ . '/stories.php';
require __DIR__ . '/memberships.php';
require __DIR__ . '/invitations.php';
require __DIR__ . '/updates.php';
require __DIR__ . '/fortify.php';
