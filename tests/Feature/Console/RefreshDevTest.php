<?php

use function Pest\Laravel\artisan;

test('Completes successfully', function () {
    artisan('app:refresh-dev')->assertSuccessful();
});

test('Fails when application environment is production', function () {
    $originalEnv = App::environment();
    app()['env'] = 'production';
    artisan('app:refresh-dev')
        ->assertFailed()
        ->expectsOutput(__('The app:refresh-dev command cannot be run in the ":env" application environment.', ['env' => App::environment()]));

    app()['env'] = $originalEnv;
});
