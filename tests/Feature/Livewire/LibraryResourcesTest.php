<?php

use App\Livewire\LibraryResources;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(LibraryResources::class)
        ->assertStatus(200);
});
