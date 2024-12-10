<?php

use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\get;

test('health page loads without response', function () {
    get(route('health'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->missing('duration')
        );
});

test('health page loads with response', function () {
    define('LARAVEL_START', microtime(true));

    get(route('health'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('duration')
        );
});
