<?php

use App\Enums\ContactMethod;

test('values', function () {
    expect(ContactMethod::Phone->value)->toEqual('phone');
    expect(ContactMethod::Email->value)->toEqual('email');
});

test('labels', function () {
    expect(ContactMethod::labels())->toEqual([
        'email' => __('Email'),
        'phone' => __('Phone'),
    ]);
});
