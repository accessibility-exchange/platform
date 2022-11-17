<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Locales Configuration
    |--------------------------------------------------------------------------
    |
    | The list of locales that are supported by the application.
    |
    */

    'supported' => [
        'en',
        'asl',
        'fr',
        'lsq',
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Supported Sign Languages
    |--------------------------------------------------------------------------
    |
    | The list of supported sign languages for each locale supported by the application.
    |
    */

    'paired_sign_language' => [
        'en' => 'asl',
        'fr' => 'lsq',
    ],
];
