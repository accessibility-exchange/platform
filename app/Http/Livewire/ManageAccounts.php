<?php

namespace App\Http\Livewire;

use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Notifications\AccountApproved;
use App\Notifications\AccountSuspended;
use App\Notifications\AccountUnsuspended;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAccounts extends Component
{
    use WithPagination;

    public string $searchQuery = '';

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
            ->layout('layouts.app-wide');
    }

    public function approveIndividualAccount(int $id)
    {
        $individual = Individual::find($id);
        $individual->user->update(['oriented_at' => now()]);

        $individual->notify(new AccountApproved($individual));

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

        $model->notify(new AccountApproved($model));

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __(':organization has been approved.', ['organization' => $model->getTranslation('name', locale())]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function suspendIndividualAccount(int $id)
    {
        $individual = Individual::find($id);
        $individual->user->update(['suspended_at' => now()]);

        $individual->notify(new AccountSuspended($individual));

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __(':account has been suspended.', ['account' => $individual->name]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function suspendAccount(int $id, string $class)
    {
        $classname = "App\\Models\\{$class}";
        $model = $classname::select('id', 'name')->with('users')->find($id);
        $model->update(['suspended_at' => now()]);

        foreach ($model->users as $user) {
            $user->update(['suspended_at' => now()]);
        }

        $model->notify(new AccountSuspended($model));

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __(':organization and its users have been suspended.', ['organization' => $model->getTranslation('name', locale())]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function unsuspendIndividualAccount(int $id)
    {
        $individual = Individual::find($id);
        $individual->user->update(['suspended_at' => null]);

        $individual->notify(new AccountUnsuspended($individual));

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __('The suspension of :account has been lifted.', ['account' => $individual->name]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function unsuspendAccount(int $id, string $class)
    {
        $classname = "App\\Models\\{$class}";
        $model = $classname::select('id', 'name')->with('users')->find($id);
        $model->update(['suspended_at' => null]);

        foreach ($model->users as $user) {
            $user->update(['suspended_at' => null]);
        }

        $model->notify(new AccountUnsuspended($model));

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __('The suspension of :organization and its users has been lifted.', ['organization' => $model->getTranslation('name', locale())]));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function search()
    {
        $this->resetPage();
    }
}
