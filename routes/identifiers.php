<?php

use App\Http\Controllers\AgeBracketController;
use App\Http\Controllers\AreaTypeController;
use App\Http\Controllers\DisabilityTypeController;
use App\Http\Controllers\EthnoracialIdentityController;
use App\Http\Controllers\GenderIdentityController;
use App\Http\Controllers\IndigenousIdentityController;

$identifiers = [
    'age-brackets' => AgeBracketController::class,
    'area-types' => AreaTypeController::class,
    'disability-types' => DisabilityTypeController::class,
    'ethnoracial-identities' => EthnoracialIdentityController::class,
    'gender-identities' => GenderIdentityController::class,
    'indigenous-identities' => IndigenousIdentityController::class,
];

foreach ($identifiers as $name => $class) {
    Route::controller($class)
        ->prefix("admin/{$name}")
        ->name("{$name}.")
        ->middleware(['auth'])
        ->group(function () {
            Route::multilingual('', 'index')
                ->name('index');
        });
}
