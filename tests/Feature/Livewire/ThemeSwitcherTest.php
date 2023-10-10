<?php

use App\Http\Livewire\ThemeSwitcher;
use App\Models\User;
use function Pest\Livewire\livewire;

test('setting theme updates current user preference', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    livewire(ThemeSwitcher::class)
        ->call('setTheme', 'dark')
        ->assertSet('theme', 'dark');

    expect($user->fresh()->theme)->toEqual('dark');
});
