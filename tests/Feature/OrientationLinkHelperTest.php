<?php

use App\Enums\UserContext;

test('user context individual', function () {
    $expected = json_decode(DB::table('settings')->where('name', 'individual_orientation')->first()->payload);
    expect(orientation_link(UserContext::Individual->value))->toEqual($expected);
});

test('user context organization', function () {
    $expected = json_decode(DB::table('settings')->where('name', 'org_orientation')->first()->payload);
    expect(orientation_link(UserContext::Organization->value))->toEqual($expected);
});

test('user context regulated-organization', function () {
    $expected = json_decode(DB::table('settings')->where('name', 'fro_orientation')->first()->payload);
    expect(orientation_link(UserContext::RegulatedOrganization->value))->toEqual($expected);
});

test('default orientation link', function () {
    expect(orientation_link(''))->toEqual('#');
});
