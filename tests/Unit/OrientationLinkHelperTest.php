<?php

test('user context individual', function () {
    expect(orientation_link('individual'))->toEqual('https://share.hsforms.com/161eyaBsQS-iv1z0TZLwdQwdfpez');
});

test('user context organization', function () {
    expect(orientation_link('organization'))->toEqual('https://share.hsforms.com/1sB6UV4gvQlC_0QxQ3q3z1Adfpez');
});

test('user context regulated-organization', function () {
    expect(orientation_link('regulated-organization'))->toEqual('https://share.hsforms.com/1gGf9TjhaQ0uaqcnyJfSDlwdfpez');
});

test('default orientation link', function () {
    expect(orientation_link(''))->toEqual('#');
});
