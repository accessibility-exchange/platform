<?php

test('a url can be normalized', function () {
    expect(normalize_url('accessibilityexchange.ca'))->toEqual('https://accessibilityexchange.ca');
});

test('a url can be normalized with a custom scheme', function () {
    expect(normalize_url('accessibilityexchange.ca', 'http://'))->toEqual('http://accessibilityexchange.ca');
});

test('an empty string will not be coerced into a url', function () {
    expect(normalize_url(''))->toEqual('');
});

test('a non-url string will not be coerced into a url', function () {
    expect(normalize_url('ceci n’est pas un URL'))->toEqual('ceci n’est pas un URL');
});
