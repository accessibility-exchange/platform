<?php

use App\Enums\ProjectContext;

test('values', function () {
    expect(ProjectContext::New->value)->toEqual('new');
    expect(ProjectContext::FollowUp->value)->toEqual('follow-up');
});

test('labels', function () {
    expect(ProjectContext::labels())->toEqual([
        'new' => __('A new project'),
        'follow-up' => __('A follow-up to a previous project (such as a progress report)'),
    ]);
});
