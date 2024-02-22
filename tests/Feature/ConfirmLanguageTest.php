<?php

use function Pest\Laravel\followingRedirects;
use function Pest\Laravel\get;
use function Pest\Laravel\withCookie;
use function Pest\Laravel\withSession;

test('confirm language state on initial visit', function () {
    get(localized_route('welcome'))
        ->assertOk()
        ->assertCookie('language-confirmed', 'true')
        ->assertSessionMissing('language-confirmed');
});

test('confirm language state on subsequent visit', function () {
    withCookie('language-confirmed', true)->get(localized_route('welcome'))
        ->assertOk()
        ->assertCookie('language-confirmed', 'true')
        ->assertSessionHas('language-confirmed', true);
});

test('confirm language state no cookie but in session', function () {
    withSession(['language-confirmed' => true])->get(localized_route('welcome'))
        ->assertOk()
        ->assertCookie('language-confirmed', 'true')
        ->assertSessionHas('language-confirmed', true);
});

test('confirm language with initial redirect', function () {
    get('/')
        ->assertStatus(302)
        ->assertCookieMissing('language-confirmed')
        ->assertSessionMissing('language-confirmed');

    followingRedirects()->get('/')
        ->assertOk()
        ->assertCookie('language-confirmed', 'true')
        ->assertSessionMissing('language-confirmed');
});
