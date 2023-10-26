<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'seeds' => [
            'driver' => 's3',
            'path' => 'seeds',
            'key' => env('MINIO_ACCESS_KEY'),
            'secret' => env('MINIO_SECRET_KEY'),
            'region' => env('MINIO_REGION'),
            'bucket' => env('MINIO_PROJECT_BUCKET'),
            'use_path_style_endpoint' => true,
            'endpoint' => 'https://'.env('MINIO_ENDPOINT'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

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

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
