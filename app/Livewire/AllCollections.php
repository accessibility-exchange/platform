<?php

namespace App\Livewire;

use App\Models\ResourceCollection;
use Livewire\Component;
use Livewire\WithPagination;

class AllCollections extends Component
{
    use WithPagination;

    public string $orderBy = 'title';

    public function render()
    {
        return view('livewire.all-collections', [
            'resourceCollections' => ResourceCollection::orderBy($this->orderBy, 'desc')->paginate(20),
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
        ])->layout('layouts.app', ['bodyClass' => 'page', 'headerClass' => 'stack full header--resource-collections', 'pageWidth' => 'wide']);
    }
}
