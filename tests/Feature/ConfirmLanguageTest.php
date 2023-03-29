<?php

test('confirm language state on initial visit', function () {
    $response = $this->get(localized_route('welcome'));

    $response->assertOk();
    $response->assertCookie('language-confirmed', 'true');
    $response->assertSessionMissing('language-confirmed');
});

test('confirm language state on subsequent visit', function () {
    $response = $this->withCookie('language-confirmed', true)->get(localized_route('welcome'));

    $response->assertOk();
    $response->assertCookie('language-confirmed', 'true');
    $response->assertSessionHas('language-confirmed', true);
});

test('confirm language state no cookie but in session', function () {
    $response = $this->withSession(['language-confirmed' => true])->get(localized_route('welcome'));

    $response->assertOk();
    $response->assertCookie('language-confirmed', 'true');
    $response->assertSessionHas('language-confirmed', true);
});

test('confirm language with initial redirect', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
    $response->assertCookieMissing('language-confirmed');
    $response->assertSessionMissing('language-confirmed');

    $response = $this->followingRedirects()->get('/');

    $response->assertOk();
    $response->assertCookie('language-confirmed', 'true');
    $response->assertSessionMissing('language-confirmed');
});
