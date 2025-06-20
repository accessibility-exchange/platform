<?php

use App\Livewire\AllCollections;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(AllCollections::class)
        ->assertStatus(200);
});
