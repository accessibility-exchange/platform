<?php

use App\Http\Livewire\ThemeSwitcher;
use App\Models\User;

test('setting theme updates current user preference', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->livewire(ThemeSwitcher::class)
        ->call('setTheme', 'dark')
        ->assertSet('theme', 'dark');

    expect($user->fresh()->theme)->toEqual('dark');
});
