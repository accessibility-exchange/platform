<?php

test('database refresh command', function () {
    $this->artisan('db:refresh')->assertSuccessful();
});

test('initial deployment command', function () {
    $this->artisan('deploy:initial')->assertSuccessful();
});
