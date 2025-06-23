<?php

use App\Livewire\AllLibraries;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(AllLibraries::class)
        ->assertStatus(200);
});
