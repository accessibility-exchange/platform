<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    public function render()
    {
        return view('livewire.manage-users', [
            'accounts' => collect([])->paginate(20),
        ])
            ->layout('layouts.app-wide');
    }

    public function search()
    {
        $this->resetPage();
    }
}
