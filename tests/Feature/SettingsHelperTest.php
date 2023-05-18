<?php

use Illuminate\Support\Facades\DB;

test('get an existing value', function () {
    // value in the database is stored as JSON
    $expected = json_decode(DB::table('settings')->where('name', 'email')->first()->payload);
    expect(settings('email'))->toBe($expected);
});

test('get a nonexistent setting', function () {
    expect(settings('example'))->toBeNull();
});

test('get a default setting', function () {
    $default = 'default value';
    expect(settings('example', $default))->toEqual($default);
});
