<?php

use App\Enums\WhoToEngage;

test('values', function () {
    expect(WhoToEngage::Individuals->value)->toEqual('individuals');
    expect(WhoToEngage::Organization->value)->toEqual('organization');
});

test('labels', function () {
    expect(WhoToEngage::labels())->toEqual([
        'individuals' => __('Individuals with lived experience of being disabled or Deaf'),
        'organization' => __('A community organization who represents or supports the disability or Deaf community'),
    ]);
});

test('markdownLabel', function () {
    expect(WhoToEngage::Individuals->markdownLabel())->toEqual(__('**Individuals** with lived experience of being disabled or Deaf'));
    expect(WhoToEngage::Organization->markdownLabel())->toEqual(__('**A community organization** who represents or supports the disability or Deaf community'));
});
