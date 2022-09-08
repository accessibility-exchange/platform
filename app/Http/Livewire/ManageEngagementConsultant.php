<?php

namespace App\Http\Livewire;

use App\Models\Engagement;
use App\Models\Project;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Livewire\Component;

class ManageEngagementConsultant extends Component
{
    public Engagement $engagement;

    public Project $project;

    public bool $seeking_accessibility_consultant;

    protected array $rules = [
        'seeking_accessibility_consultant' => 'nullable|boolean',
    ];

    public function __invoke(Container $container, Route $route, ?Engagement $engagement = null)
    {
        return parent::__invoke($container, $route);
    }

    public function mount()
    {
        $this->project = $this->engagement->project;
        $this->seeking_accessibility_consultant = $this->engagement->extra_attributes->get('seeking_accessibility_consultant', 0);
    }

    public function render()
    {
        return view('livewire.manage-engagement-consultant')
            ->layout('layouts.app-medium');
    }

    public function updateStatus()
    {
        $this->validate();

        $this->engagement->extra_attributes->set('seeking_accessibility_consultant', $this->seeking_accessibility_consultant);

        $this->engagement->save();

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __('Your engagement has been updated.'));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }
}
