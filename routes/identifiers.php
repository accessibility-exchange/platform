<?php

use App\Http\Controllers\AgeBracketController;
use App\Http\Controllers\AreaTypeController;
use App\Http\Controllers\DisabilityTypeController;
use App\Http\Controllers\EmploymentStatusController;
use App\Http\Controllers\EthnoracialIdentityController;
use App\Http\Controllers\GenderIdentityController;
use App\Http\Controllers\IndigenousIdentityController;

Route::prefix('admin')->group(function () {
    Route::resource('age-brackets', AgeBracketController::class)
        ->middleware(['auth'])
        ->only(['index']);
    Route::resource('area-types', AreaTypeController::class)
        ->middleware(['auth'])
        ->only(['index']);
    Route::resource('disability-types', DisabilityTypeController::class)
        ->middleware(['auth'])
        ->only(['index']);
    Route::resource('employment-statues', EmploymentStatusController::class)
        ->middleware(['auth'])
        ->only(['index']);
    Route::resource('ethnoracial-identities', EthnoracialIdentityController::class)
        ->middleware(['auth'])
        ->only(['index']);
    Route::resource('gender-identities', GenderIdentityController::class)
        ->middleware(['auth'])
        ->only(['index']);
    Route::resource('indigenous-identities', IndigenousIdentityController::class)
        ->middleware(['auth'])
        ->only(['index']);
});
