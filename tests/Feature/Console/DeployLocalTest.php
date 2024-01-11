<?php

use function Pest\Laravel\artisan;

afterEach(function () {
    artisan('optimize:clear');
    artisan('icons:clear');
    artisan('event:clear');
});

test('Completes successfully', function () {
    artisan('deploy:local')->assertSuccessful();
});
