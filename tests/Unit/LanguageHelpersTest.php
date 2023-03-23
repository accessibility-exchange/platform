<?php

test('identify a signed language', function () {
    expect(is_signed_language('asl'))->toBeTrue();
    expect(is_signed_language('en'))->toBeFalse();
});

test('get supported locales', function () {
    expect(get_supported_locales())->toMatchArray(config('locales.supported'));
    expect(get_supported_locales(false))->toEqualCanonicalizing(['en', 'fr']);
});

test('get available languages', function () {
    $languages = get_available_languages();

    expect($languages)->toHaveCount(4)->toHaveKey('asl');
    expect(array_values($languages)['0'])->toEqual('English');
    expect(array_values($languages)['3'])->toEqual('Quebec Sign Language');
});

test('get all available languages', function () {
    $languages = get_available_languages(true);

    expect(array_shift($languages))->toEqual('English');
    expect(array_shift($languages))->toEqual('American Sign Language');

    expect($languages)->toHaveKey('es');
    expect(isset($languages['en_CA']))->toBeFalse();
    expect(isset($languages['fr_CA']))->toBeFalse();
});

test('get a signed language exonym', function () {
    expect(get_language_exonym('asl', 'en'))->toEqual('American Sign Language');
});

test('get a written or spoken language exonym', function () {
    expect(get_language_exonym('fr', 'fr', false))->toEqual('français');
});

test('get a capitalized written or spoken language exonym', function () {
    expect(get_language_exonym('fr', 'fr'))->toEqual('Français');
});

test('get an invalid language exonym', function () {
    expect(get_language_exonym('xyz'))->toBeNull();
});

test('get written language for signed language', function () {
    expect(get_written_language_for_signed_language('asl'))->toEqual('en');
    expect(get_written_language_for_signed_language('lsq'))->toEqual('fr');
});

test('get signed language for written language', function () {
    expect(get_signed_language_for_written_language('en'))->toEqual('asl');
    expect(get_signed_language_for_written_language('fr'))->toEqual('lsq');
});
