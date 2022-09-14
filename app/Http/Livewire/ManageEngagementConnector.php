<?php

namespace App\Http\Livewire;

use App\Models\Engagement;
use App\Models\Individual;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Route;
use Livewire\Component;

class ManageEngagementConnector extends Component
{
    use AuthorizesRequests;

    public Engagement $engagement;

    public Project $project;

    public ?Invitation $invitation;

    public null|Individual|Organization $invitee;

    public bool $seeking_community_connector;

    protected array $rules = [
        'seeking_community_connector' => 'nullable|boolean',
    ];

    public function __invoke(Container $container, Route $route, ?Engagement $engagement = null)
    {
        return parent::__invoke($container, $route);
    }

    public function mount()
    {
        $this->project = $this->engagement->project;
        $this->seeking_community_connector = $this->engagement->extra_attributes->get('seeking_community_connector', 0);
        $this->invitation = $this->engagement->invitations->where('role', 'connector')->first() ?? null;
        if ($this->invitation) {
            if ($this->invitation->type === 'individual') {
                $individual = User::whereBlind('email', 'email_index', $this->invitation->email)->first()->individual ?? null;
                $this->invitee = $individual && $individual->checkStatus('published') ? $individual : null;
            } elseif ($this->invitation->type === 'organization') {
                $this->invitee = Organization::where('contact_person_email', $this->invitation->email)->first() ?? null;
            }
        } else {
            $this->invitee = null;
        }
    }

    public function render()
    {
        return view('livewire.manage-engagement-connector')
            ->layout('layouts.app-medium');
    }

    public function updateStatus()
    {
        $this->authorize('update', $this->engagement);

        $this->validate();

        $this->engagement->extra_attributes->set('seeking_community_connector', $this->seeking_community_connector);

        $this->engagement->save();

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __('Your engagement has been updated.'));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function cancelInvitation()
    {
        $this->authorize('update', $this->engagement);

        $this->invitation->delete();

        $this->invitation = null;

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __('Your invitation has been cancelled.'));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }

    public function removeConnector()
    {
        $this->authorize('update', $this->engagement);

        if ($this->engagement->connector) {
            $this->engagement->connector()->dissociate();
        }

        if ($this->engagement->organizationalConnector) {
            $this->engagement->organizationalConnector()->dissociate();
        }

        $this->engagement->save();

        $this->dispatchBrowserEvent('clear-flash-message');

        session()->flash('message', __('Your Community Connector has been removed.'));

        $this->dispatchBrowserEvent('add-flash-message');

        $this->dispatchBrowserEvent('remove-flash-message');
    }
}
