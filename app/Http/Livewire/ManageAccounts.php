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

    public function render()
    {
        $individuals = new Collection(Individual::all());
        $organizations = new Collection(Organization::all());
        $regulatedOrganizations = new Collection(RegulatedOrganization::all());

        $accounts = $individuals
            ->merge($organizations)
            ->merge($regulatedOrganizations);

        return view('livewire.manage-accounts', [
            'accounts' => $accounts->paginate(20),
        ])
            ->layout('layouts.app-wide');
    }

    public function approveIndividualAccount(int $id)
    {
        $individual = Individual::find($id);
        $individual->user->update(['oriented_at' => now()]);

        // TODO: Notifications

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __(':account has been approved.', ['account' => $individual->name]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function approveAccount(int $id, string $class)
    {
        $classname = "App\\Models\\{$class}";
        $model = $classname::find($id);
        $model->update(['oriented_at' => now(), 'validated_at' => now()]);

        // TODO: Notifications

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __(':account has been approved.', ['account' => $model->getTranslation('name', locale())]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function search()
    {
        $this->resetPage();
    }
}
