<?php

use App\Enums\UserContext;
use App\Settings\GeneralSettings;

test('user context individual', function () {
    $generalSettings = GeneralSettings::fake(['individual_orientation' => ['en' => 'english_link', 'fr' => 'french_link']]);
    expect(orientation_link(UserContext::Individual->value))->toEqual($generalSettings->individual_orientation['en']);
    App::setLocale('fr');
    expect(orientation_link(UserContext::Individual->value))->toEqual($generalSettings->individual_orientation['fr']);
});

test('user context organization', function () {
    $generalSettings = GeneralSettings::fake(['org_orientation' => ['en' => 'english_link', 'fr' => 'french_link']]);
    expect(orientation_link(UserContext::Organization->value))->toEqual($generalSettings->org_orientation['en']);
    App::setLocale('fr');
    expect(orientation_link(UserContext::Organization->value))->toEqual($generalSettings->org_orientation['fr']);
});

test('user context regulated-organization', function () {
    $generalSettings = GeneralSettings::fake(['fro_orientation' => ['en' => 'english_link', 'fr' => 'french_link']]);
    expect(orientation_link(UserContext::RegulatedOrganization->value))->toEqual($generalSettings->fro_orientation['en']);
    App::setLocale('fr');
    expect(orientation_link(UserContext::RegulatedOrganization->value))->toEqual($generalSettings->fro_orientation['fr']);
});

test('default orientation link', function () {
    expect(orientation_link(''))->toEqual('#');
});
