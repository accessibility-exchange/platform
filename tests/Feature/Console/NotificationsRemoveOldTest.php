<?php

use function Pest\Laravel\artisan;

test('Runs with valid days specified', function () {
    artisan('notifications:remove:old', ['--days' => 30])->assertSuccessful();
});

test('Fails without valid days', function () {
    artisan('notifications:remove:old')
        ->assertFailed()
        ->expectsOutput(__('Must specify a number of days greater than 1 using the "--days" flag. Example --days=30'));
    artisan('notifications:remove:old', ['--days' => 'five'])
        ->assertFailed()
        ->expectsOutput(__('Must specify a number of days greater than 1 using the "--days" flag. Example --days=30. The specified "--days=:days" is invalid.', ['days' => 'five']));
    artisan('notifications:remove:old', ['--days' => 0])
        ->assertFailed()
        ->expectsOutput(__('Must specify a number of days greater than 1 using the "--days" flag. Example --days=30. The specified "--days=:days" is invalid.', ['days' => 0]));
});
