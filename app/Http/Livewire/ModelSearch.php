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

    public ?bool $selectable;

    public mixed $selection;

    public string $component = 'card';

    public int $level = 2;

    public function mount(bool $selectable = false)
    {
        $this->label = $this->label ?? __('Search');
        $this->selectable = $selectable;
        $this->query = '';
        $this->results = new Collection([]);
    }

    public function search()
    {
        $this->results = $this->model::where('name->en', 'like', '%'.$this->query.'%')
            ->orWhere('name->fr', 'like', '%'.$this->query.'%')
            ->get();
    }

    public function sendSelection(int $id)
    {
        $this->selection = $this->model::find($id);
        $this->emitUp('sendSelection', $id);
    }

    public function render()
    {
        return view('livewire.model-search');
    }
}
