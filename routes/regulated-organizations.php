<?php

use App\Http\Controllers\RegulatedOrganizationController;

Route::multilingual('/regulated-organizations', [RegulatedOrganizationController::class, 'index'])
    ->middleware(['auth'])
    ->name('regulated-organizations.index');

Route::multilingual('/regulated-organizations/create', [RegulatedOrganizationController::class, 'create'])
    ->middleware(['auth', 'can:create,App\Models\RegulatedOrganization'])
    ->name('regulated-organizations.create');

Route::multilingual('/regulated-organizations/create', [RegulatedOrganizationController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'can:create,App\Models\RegulatedOrganization'])
    ->name('regulated-organizations.store');

Route::multilingual('/regulated-organizations/{regulatedOrganization}', [RegulatedOrganizationController::class, 'show'])
    ->middleware(['auth'])
    ->name('regulated-organizations.show');

Route::multilingual('/regulated-organizations/{regulatedOrganization}/accessibility-and-inclusion', [RegulatedOrganizationController::class, 'show'])
    ->middleware(['auth'])
    ->name('regulated-organizations.show-accessibility-and-inclusion');

 Route::multilingual('/regulated-organizations/{regulatedOrganization}/projects', [RegulatedOrganizationController::class, 'show'])
    ->middleware(['auth'])
    ->name('regulated-organizations.show-projects');

Route::multilingual('/regulated-organizations/{regulatedOrganization}/edit', [RegulatedOrganizationController::class, 'edit'])
    ->middleware(['auth', 'can:update,regulatedOrganization'])
    ->name('regulated-organizations.edit');

Route::multilingual('/regulated-organizations/{regulatedOrganization}/edit', [RegulatedOrganizationController::class, 'update'])
    ->middleware(['auth', 'can:update,regulatedOrganization'])
    ->method('put')
    ->name('regulated-organizations.update');

Route::multilingual('/regulated-organizations/{regulatedOrganization}/delete', [RegulatedOrganizationController::class, 'destroy'])
    ->middleware(['auth', 'can:delete,regulatedOrganization'])
    ->method('delete')
    ->name('regulated-organizations.destroy');
