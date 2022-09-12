<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class ModelSearch extends Component
{
    public string $model;

    public string $label;

    public string $query;

    public Collection $results;

    public mixed $selection;

    public function mount()
    {
        $this->label = $this->label ?? __('Search');
        $this->query = '';
        $this->results = new Collection([]);
    }

    public function search()
    {
        $this->results = $this->model::where('name->en', 'like', '%'.$this->query.'%')
            ->orWhere('name->fr', 'like', '%'.$this->query.'%')
            ->get();
    }

    public function render()
    {
        return view('livewire.model-search');
    }
}
