<?php

test('get a nonexistent setting', function () {
    $this->assertNull(settings('example'));
});

test('get a default setting', function () {
    $this->assertEquals(settings('example', 'default value'), 'default value');
});

test('put a setting', function () {
    settings()->put('example', 'foo');

    $this->assertEquals(settings('example'), 'foo');

    settings()->forget('example');
});

test('forget a setting', function () {
    settings()->put('example', 'foo');
    settings()->forget('example');
    $this->assertNull(settings('example'));
});
