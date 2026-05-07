<?php

namespace App\Livewire;

use App\Enums\UserContext;
use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\LaravelOptions\Options;

class ManageAccounts extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    public string $accountType = '';

    protected $listeners = ['flashMessage' => 'flash'];

    protected $queryString = ['searchQuery' => ['except' => '', 'as' => 'search'],
        'accountType' => ['except' => '', 'as' => 'type'], ];

    private function shouldInclude(UserContext $type): bool
    {
        return $this->accountType === '' || $this->accountType === $type->value;
    }

    public function render()
    {
        $accounts = new Collection;

        if ($this->shouldInclude(UserContext::Individual)) {
            $accounts = $accounts->merge(
                $this->searchQuery
                    ? Individual::whereHas('user', function (Builder $query) {
                        $query->whereBlind('name', 'name_index', $this->searchQuery);
                    })->get()
                    : Individual::all()
            );
        }

        if ($this->shouldInclude(UserContext::Organization)) {
            $accounts = $accounts->merge(
                $this->searchQuery
                    ? Organization::where('name->en', 'like', '%'.$this->searchQuery.'%')
                        ->orWhere('name->fr', 'like', '%'.$this->searchQuery.'%')->get()
                    : Organization::all()
            );
        }

        if ($this->shouldInclude(UserContext::RegulatedOrganization)) {
            $accounts = $accounts->merge(
                $this->searchQuery
                    ? RegulatedOrganization::where('name->en', 'like', '%'.$this->searchQuery.'%')
                        ->orWhere('name->fr', 'like', '%'.$this->searchQuery.'%')->get()
                    : RegulatedOrganization::all()
            );
        }

        $accounts = $accounts->sortBy(fn ($item) => $item->name);

        return view('livewire.manage-accounts', [
            'accounts' => $accounts->paginate(20),
            'accountTypeOptions' => Options::forEnum(UserContext::class)
                ->only(UserContext::Individual, UserContext::Organization, UserContext::RegulatedOrganization)
                ->nullable(__('All account types'))
                ->toArray(),
            'accountTypeLabel' => UserContext::labels()[$this->accountType] ?? '',
        ])
            ->layout('layouts.app', ['bodyClass' => 'page', 'headerClass' => 'stack', 'pageWidth' => 'wide']);
    }

    public function flash(string $message, ?string $interpretation = null)
    {
        $this->dispatch('clear-flash-message');
        session()->flash('message', $message);
        if (isset($interpretation)) {
            session()->flash('message-interpretation', $interpretation);
        }
        $this->dispatch('add-flash-message');
    }

    public function search()
    {
        $this->resetPage();
    }

    public function updated()
    {
        $this->resetPage();
    }
}
