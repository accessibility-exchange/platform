<?php

namespace App\Livewire;

use App\Models\ResourceCollection;
use Livewire\Component;
use Livewire\WithPagination;

class AllCollections extends Component
{
    use WithPagination;

    public string $orderBy;

    public function mount()
    {
        $lang = app()->getLocale();

        $this->orderBy = "title->$lang";
    }

    public function render()
    {
        $lang = app()->getLocale();

        return view('livewire.all-collections', [
            'resourceCollections' => ResourceCollection::orderBy($this->orderBy, $this->orderBy === "title->$lang" ? 'asc' : 'desc')->paginate(20),
            'orderOptions' => [
                [
                    'value' => "title->$lang",
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
