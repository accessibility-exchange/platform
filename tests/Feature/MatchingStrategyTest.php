<?php

use App\Models\MatchingStrategy;
use App\Models\User;

test('administrators can access matching strategies', function () {
    $user = User::factory()->create();
    $administrator = User::factory()->create(['context' => 'administrator']);

    $strategy = MatchingStrategy::factory()->create();

    expect($user->can('view', $strategy))->toBeFalse();
    expect($administrator->can('view', $strategy))->toBeTrue();
});
