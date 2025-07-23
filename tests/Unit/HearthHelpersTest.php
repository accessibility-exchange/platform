<?php

use App\Enums\ProvinceOrTerritory;

test('get region name in default locale', function () {
    $result = get_region_name(ProvinceOrTerritory::NovaScotia->value, ['CA']);
    expect($result)->toEqual('Nova Scotia');
});

test('get region name in alternate locale', function () {
    $result = get_region_name(ProvinceOrTerritory::NovaScotia->value, ['CA'], 'fr');
    expect($result)->toEqual('Nouvelle-Écosse');
});

test('get region name returns null for invalid region', function () {
    $result = get_region_name(ProvinceOrTerritory::NovaScotia->value, ['US']);
    expect($result)->toBeNull();
});

test('get regions in default locale', function () {
    $result = get_regions(['CA']);
    expect($result)->toContain(['value' => ProvinceOrTerritory::NovaScotia->value, 'label' => 'Nova Scotia']);
});

test('get regions in alternate locale', function () {
    $result = get_regions(['CA'], 'fr');
    expect($result)->toContain(['value' => ProvinceOrTerritory::NovaScotia->value, 'label' => 'Nouvelle-Écosse']);
});

test('get region codes', function () {
    $result = get_region_codes(['CA']);
    expect($result)->toContain(ProvinceOrTerritory::NovaScotia->value);
});

test('get locale name', function () {
    $result = get_locale_name('fr');
    expect($result)->toEqual('French');

    $result = get_locale_name('en', 'fr');
    expect($result)->toEqual('Anglais');

    $result = get_locale_name('en', 'fr', false);
    expect($result)->toEqual('anglais');

    $result = get_locale_name('zz');
    expect($result)->toBeNull();
});
