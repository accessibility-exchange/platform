<?php

namespace App\Http\Livewire;

use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAccounts extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    public function render()
    {
        $individuals = Individual::all();
        $organizations = Organization::all();
        $regulatedOrganizations = RegulatedOrganization::all();

        $accounts = $individuals
            ->merge($organizations)
            ->merge($regulatedOrganizations);

        return view('livewire.manage-accounts', [
            'accounts' => $accounts->paginate(20),
        ])
            ->layout('layouts.app-wide');
    }

    public function search()
    {
        $this->resetPage();
    }
}
