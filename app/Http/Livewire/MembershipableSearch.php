<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class MembershipableSearch extends Component
{
    public string $membershipable;

    public string $query;

    public Collection $results;

    public function mount()
    {
        $this->query = '';
        $this->results = new Collection([]);
    }

    public function search()
    {
        $this->results = $this->membershipable::where('name->en', 'like', '%' . $this->query . '%')
            ->orWhere('name->fr', 'like', '%' . $this->query . '%')
            ->get();
    }

    public function render()
    {
        return view('livewire.membershipable-search');
    }
}
