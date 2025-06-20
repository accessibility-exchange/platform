<?php

use App\Livewire\ShowLibrary;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ShowLibrary::class)
        ->assertStatus(200);
});
