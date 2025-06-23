<?php

namespace App\Livewire;

use App\Models\Library;
use Livewire\Component;
use Livewire\WithPagination;

class AllLibraries extends Component
{
    use WithPagination;

    public string $orderBy = 'title';

    public function render()
    {
        return view('livewire.all-libraries', [
            'libraries' => Library::orderBy($this->orderBy, $this->orderBy === title ? 'asc' : 'desc')->paginate(20),
            'orderOptions' => [
                [
                    'value' => 'title',
                    'label' => __('Alphabetical order'),
                ],
                [
                    'value' => 'created_at',
                    'label' => __('Latest added'),
                ],
            ],
        ])->layout('layouts.app', ['bodyClass' => 'page', 'headerClass' => 'stack full header--libraries', 'pageWidth' => 'wide']);
    }
}
