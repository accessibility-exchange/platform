<?php

return [
    'disks' => [
        'snapshots' => [
            'driver' => 'local',
            'root' => database_path('snapshots'),
        ],
        'snapshots-s3' => [
            'driver' => 's3',
            'key' => env('SNAPSHOTS_AWS_ACCESS_KEY_ID'),
            'secret' => env('SNAPSHOTS_AWS_SECRET_ACCESS_KEY'),
            'region' => env('SNAPSHOTS_AWS_DEFAULT_REGION'),
            'bucket' => env('SNAPSHOTS_AWS_BUCKET'),
            'url' => env('SNAPSHOTS_AWS_URL'),
            'endpoint' => env('SNAPSHOTS_AWS_ENDPOINT'),
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],
    ],
    'links' => [
        public_path('sitemap.xml') => storage_path('app/public/sitemap.xml'),
        public_path('robots.txt') => storage_path('app/public/robots.txt'),
        lang_path('lsq') => lang_path('fr'),
        lang_path('lsq.json') => lang_path('fr.json'),
        lang_path('vendor/hearth-components/lsq') => lang_path('vendor/hearth-components/fr'),
    ],
];
