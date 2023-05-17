<?php

namespace App\Http\Livewire;

use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAccounts extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    protected $listeners = ['flashMessage' => 'flash'];

    protected $queryString = ['searchQuery' => ['except' => '', 'as' => 'search']];

    public function render()
    {
        $individuals = new Collection(
            $this->searchQuery ?
                Individual::whereBlind('name', 'name_index', $this->searchQuery)->get() :
                Individual::all()
        );
        $organizations = new Collection(
            $this->searchQuery ?
                Organization::where('name->en', 'like', '%'.$this->searchQuery.'%')
                    ->orWhere('name->fr', 'like', '%'.$this->searchQuery.'%')->get() :
                Organization::all()
        );
        $regulatedOrganizations = new Collection(
            $this->searchQuery ?
                RegulatedOrganization::where('name->en', 'like', '%'.$this->searchQuery.'%')
                    ->orWhere('name->fr', 'like', '%'.$this->searchQuery.'%')->get() :
                RegulatedOrganization::all()
        );

        $accounts = $individuals
            ->merge($organizations)
            ->merge($regulatedOrganizations)
            ->sortBy(fn ($item) => $item->name);

        return view('livewire.manage-accounts', [
            'accounts' => $accounts->paginate(20),
        ])
        ->layout('layouts.app', ['bodyClass' => 'page', 'headerClass' => 'stack', 'pageWidth' => 'wide']);
    }

    public function flash(string $message)
    {
        $this->dispatchBrowserEvent('clear-flash-message');
        session()->flash('message', $message);
        $this->dispatchBrowserEvent('add-flash-message');
    }

    public function search()
    {
        $this->resetPage();
    }
}
