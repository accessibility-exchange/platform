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

test('get a settings value when locale is English', function () {
    $expected = json_decode(DB::table('settings')->where('name', 'individual_orientation')->first()->payload, true)['en'];
    expect(settings_localized('individual_orientation', 'en'))->toBe($expected);
});

test('get a settings value when locale is lsq', function () {
    App::setLocale('fr');
    $expected = json_decode(DB::table('settings')->where('name', 'individual_orientation')->first()->payload, true)['fr'];
    expect(settings_localized('individual_orientation', 'lsq'))->toBe($expected);
});

test('get a default settings value when locale is not supported', function () {
    $expected = json_decode(DB::table('settings')->where('name', 'individual_orientation')->first()->payload, true)['en'];
    expect(settings_localized('individual_orientation', 'xy'))->toBe($expected);
});
