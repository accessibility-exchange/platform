<?php

use App\Http\Controllers\MembershipController;

Route::multilingual('/memberships/{membership}/edit', [MembershipController::class, 'edit'])
    ->name('memberships.edit');

Route::multilingual('/memberships/{membership}/update', [MembershipController::class, 'update'])
    ->method('put')
    ->name('memberships.update');

Route::delete('/memberships/{membership}/delete', [MembershipController::class, 'destroy'])
    ->name('memberships.destroy');
