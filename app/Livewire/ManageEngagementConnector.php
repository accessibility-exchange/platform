<?php

namespace App\Livewire;

use App\Models\Engagement;
use App\Models\Individual;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\Project;
use App\Traits\RetrievesUserByNormalizedEmail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class ManageEngagementConnector extends Component
{
    use AuthorizesRequests, RetrievesUserByNormalizedEmail;

    public Engagement $engagement;

    public Project $project;

    public ?Invitation $invitation;

    public null|Individual|Organization $invitee;

    public bool $seeking_community_connector;

    protected array $rules = [
        'seeking_community_connector' => 'nullable|boolean',
    ];

    public function mount(Engagement $engagement)
    {
        $this->authorize('update', $engagement);

        $this->engagement = $engagement;
        $this->project = $this->engagement->project;
        $this->seeking_community_connector = $this->engagement->extra_attributes->get('seeking_community_connector', 0);
        $this->invitation = $this->engagement->invitations->where('role', 'connector')->first() ?? null;
        if ($this->invitation) {
            if ($this->invitation->type === 'individual') {
                $individual = $this->retrieveUserByEmail($this->invitation->email)?->individual;
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
            ->layout('layouts.app', ['bodyClass' => 'page', 'headerClass' => 'stack', 'pageWidth' => 'medium']);
    }

    public function updateStatus()
    {
        $this->authorize('update', $this->engagement);

        $this->validate();

        $this->engagement->extra_attributes->set('seeking_community_connector', $this->seeking_community_connector);

        $this->engagement->save();

        $this->dispatch('clear-flash-message');

        session()->flash('message', __('Your engagement has been updated.'));
        session()->flash('message-interpretation', __('Your engagement has been updated.', [], 'en'));

        $this->dispatch('add-flash-message');
    }

    public function cancelInvitation()
    {
        $this->authorize('update', $this->engagement);

        $this->invitation->delete();

        $notifications = DatabaseNotification::where('data->invitation_id', $this->invitation->id)->get();

        foreach ($notifications as $notification) {
            $notification->delete();
        }

        $this->invitation = null;

        $this->dispatch('clear-flash-message');

        session()->flash('message', __('Your invitation has been cancelled.'));
        session()->flash('message-interpretation', __('Your invitation has been cancelled.', [], 'en'));

        $this->dispatch('add-flash-message');
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

        $this->dispatch('clear-flash-message');

        session()->flash('message', __('Your Community Connector has been removed.'));
        session()->flash('message-interpretation', __('Your Community Connector has been removed.', [], 'en'));

        $this->dispatch('add-flash-message');
    }
}
