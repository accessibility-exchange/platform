<?php

use function Pest\Laravel\artisan;

test('Completes successfully', function () {
    artisan('deploy:global')->assertSuccessful();

    // clean up
    artisan('migrate:fresh');
});
