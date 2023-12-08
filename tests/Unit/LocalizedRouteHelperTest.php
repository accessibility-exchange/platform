<?php

use App\Models\RegulatedOrganization;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->model = RegulatedOrganization::factory()->create([
        'name' => [
            'en' => 'test org',
            'fr' => 'teste org',
        ],
    ]);

    $this->currentLocale = locale();
});

afterEach(function () {
    locale($this->currentLocale);
});

test('get localized route without requested locale', function () {
    locale('fr');
    $url = localized_route_for_locale('regulated-organizations.show', $this->model);

    expect($url)->toBe(route('fr.regulated-organizations.show', $this->model));
});

test('localized_route_for_locale with requested locale the same as app locale', function () {
    locale('fr');
    $url = localized_route_for_locale('regulated-organizations.show', $this->model, 'fr');

    expect($url)->toBe(route('fr.regulated-organizations.show', $this->model));
});

test('localized_route_for_locale with requested locale', function () {
    locale('en');
    $url = localized_route_for_locale('regulated-organizations.show', $this->model, 'fr');

    expect($url)->toBe(Str::replace('test-org', 'teste-org', route('fr.regulated-organizations.show', $this->model)));
});

test('get route name unlocalized', function (string $locale) {
    $routeName = 'test.route';
    $route = new Route('GET', '\test-route', function () {
    });
    $route->name("{$locale}.{$routeName}");

    expect(route_name($route))->toBe($routeName);
})->with('supportedLocales');

test('get route name localized', function (string $locale) {
    $routeName = 'test.route';
    $localizedRouteName = "{$locale}.{$routeName}";
    $route = new Route('GET', '\test-route', function () {
    });
    $route->name($localizedRouteName);

    expect(route_name($route, true))->toBe($localizedRouteName);
})->with('supportedLocales');
