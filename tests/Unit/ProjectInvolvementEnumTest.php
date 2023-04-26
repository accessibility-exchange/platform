<?php

use App\Enums\ProjectInvolvement;

test('values', function () {
    expect(ProjectInvolvement::Contracted->value)->toEqual('contracted');
    expect(ProjectInvolvement::Participating->value)->toEqual('participating');
    expect(ProjectInvolvement::Running->value)->toEqual('running');
});

test('labels', function () {
    expect(ProjectInvolvement::labels())->toEqual([
        'contracted' => __('Contracted'),
        'participating' => __('Participating'),
        'running' => __('Running'),
    ]);
});
