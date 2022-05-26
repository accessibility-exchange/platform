
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

Route::multilingual('/organizations/{organization}/join', [OrganizationController::class, 'join'])
    ->method('post')
    ->middleware(['auth', 'can:join,organization'])
    ->name('organizations.join');

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
    ->middleware(['auth', 'signed'])
    ->name('invitations.accept');

Route::delete('/invitations/{invitation}/cancel', [InvitationController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('invitations.destroy');
