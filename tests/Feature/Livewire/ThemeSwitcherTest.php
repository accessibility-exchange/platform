<?php

use App\Enums\Theme;
use App\Livewire\ThemeSwitcher;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

test('setting theme updates current user preference', function () {
    $user = User::factory()->create();
    actingAs($user);

    livewire(ThemeSwitcher::class)
        ->call('setTheme', Theme::Dark->value)
        ->assertSet('theme', Theme::Dark->value);

    expect($user->fresh()->theme)->toEqual(Theme::Dark->value);
});
