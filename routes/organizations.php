<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

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
