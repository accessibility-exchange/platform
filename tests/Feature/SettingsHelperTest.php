<?php

use App\Settings\GeneralSettings;

test('get an existing value', function () {
    // value in the database is stored as JSON
    $generalSettings = GeneralSettings::fake(['email' => 'support@accessibilityexchange.ca']);
    expect(settings('email'))->toBe($generalSettings->email);
});

test('get a nonexistent setting', function () {
    $generalSettings = GeneralSettings::fake([]);
    expect(settings('example'))->toBeNull();
});

test('get a default setting', function () {
    $default = 'default value';
    $generalSettings = GeneralSettings::fake(['example' => $default]);
    expect(settings('example', $default))->toEqual($generalSettings->example);
});

test('get a settings value when locale is English', function () {
    $generalSettings = GeneralSettings::fake(['individual_orientation' => ['en' => 'english_link', 'fr' => 'french_link']]);
    expect(settings_localized('individual_orientation', 'en'))->toBe($generalSettings->individual_orientation['en']);
});

test('get a settings value when locale is lsq', function () {
    $generalSettings = GeneralSettings::fake(['individual_orientation' => ['en' => 'english_link', 'fr' => 'french_link']]);
    expect(settings_localized('individual_orientation', 'lsq'))->toBe($generalSettings->individual_orientation['fr']);
});

test('get a default settings value when locale is not supported', function () {
    $generalSettings = GeneralSettings::fake(['individual_orientation' => ['en' => 'english_link', 'fr' => 'french_link']]);
    expect(settings_localized('individual_orientation', 'xy'))->toBe($generalSettings->individual_orientation['en']);
});
