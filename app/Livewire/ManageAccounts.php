<?php

namespace App\Livewire;

use App\Models\Individual;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class ManageAccounts extends Component
{
    use WithPagination;

    public string $searchQuery = '';

    public string $accountType = '';

    protected $listeners = ['flashMessage' => 'flash'];

    protected $queryString = ['searchQuery' => ['except' => '', 'as' => 'search'],
        'accountType' => ['except' => '', 'as' => 'type'], ];

    private const TYPE_INDIVIDUAL = 'Individual';

    private const TYPE_ORGANIZATION = 'Organization';

    private const TYPE_REGULATED_ORGANIZATION = 'Regulated organization';

    private function shouldInclude(string $type): bool
    {
        return $this->accountType === '' || $this->accountType === $type;
    }

    public function render()
    {
        $individuals = new Collection;
        $organizations = new Collection;
        $regulatedOrganizations = new Collection;

        if ($this->shouldInclude(self::TYPE_INDIVIDUAL)) {
            $individuals = new Collection(
                $this->searchQuery ?
                    Individual::whereHas('user', function (Builder $query) {
                        $query->whereBlind('name', 'name_index', $this->searchQuery);
                    })->get() :
                    Individual::all()
            );
        }

        if ($this->shouldInclude(self::TYPE_ORGANIZATION)) {
            $organizations = new Collection(
                $this->searchQuery ?
                    Organization::where('name->en', 'like', '%'.$this->searchQuery.'%')
                        ->orWhere('name->fr', 'like', '%'.$this->searchQuery.'%')->get() :
                    Organization::all()
            );
        }

        if ($this->shouldInclude(self::TYPE_REGULATED_ORGANIZATION)) {
            $regulatedOrganizations = new Collection(
                $this->searchQuery ?
                    RegulatedOrganization::where('name->en', 'like', '%'.$this->searchQuery.'%')
                        ->orWhere('name->fr', 'like', '%'.$this->searchQuery.'%')->get() :
                    RegulatedOrganization::all()
            );
        }

        $accounts = $individuals
            /** @phpstan-ignore argument.type */
            ->merge($organizations)
            /** @phpstan-ignore argument.type */
            ->merge($regulatedOrganizations)
            ->sortBy(fn ($item) => $item->name);

        return view('livewire.manage-accounts', [
            'accounts' => $accounts->paginate(20),
            'accountTypeOptions' => [
                ['value' => '', 'label' => __('All account types')],
                ['value' => self::TYPE_INDIVIDUAL, 'label' => __('Individual')],
                ['value' => self::TYPE_ORGANIZATION, 'label' => __('Organization')],
                ['value' => self::TYPE_REGULATED_ORGANIZATION, 'label' => __('Regulated organization')],
            ],
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

    public function getAccountTypeLabelProperty(): string
    {
        return match ($this->accountType) {
            self::TYPE_INDIVIDUAL => __('Individual'),
            self::TYPE_ORGANIZATION => __('Organization'),
            self::TYPE_REGULATED_ORGANIZATION => __('Regulated organization'),
            default => '',
        };
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
