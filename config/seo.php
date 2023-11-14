<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Search Engine Optimization
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration values related to search engine optimization.
    |
    */

    'sitemap' => [
        'patterns' => '*.about.*',
        'to_ignore' => [
            '*.about.page',
        ],
        'pages' => [
            '*.about.terms-of-service',
            '*.about.privacy-policy',
        ],
    ],
];
