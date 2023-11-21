<?php

/**
 * @see https://spatie.be/docs/ray/v1/configuration/laravel
 */

return [
    /*
     *  The host used to communicate with the Ray app.
     */
    'host' => env('RAY_HOST', 'localhost'),

    /*
     *  The port number used to communicate with the Ray app.
     */
    'port' => env('RAY_PORT', 23517),

    /*
     *  Absolute base path for your sites or projects in Homestead, Vagrant, Docker, or another remote development
     *  server.
     */
    'remote_path' => null,

    /*
     *  Absolute base path for your sites or projects on your local computer where your IDE or code editor is running
     *  on.
     */
    'local_path' => null,
];
