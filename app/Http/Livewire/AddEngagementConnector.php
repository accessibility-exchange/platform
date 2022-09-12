<?php

namespace App\Http\Livewire;

use App\Mail\ContractorInvitation;
use App\Models\Engagement;
use App\Models\Organization;
use App\Models\Project;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AddEngagementConnector extends Component
{
    public Engagement $engagement;

    public Project $project;

    public string $who;

    public ?string $email;

    public ?Organization $organization;

    public function __invoke(Container $container, Route $route, ?Engagement $engagement = null)
    {
        return parent::__invoke($container, $route);
    }

    public function mount()
    {
        $this->project = $this->engagement->project;
        $this->who = '';
        $this->email = '';
        $this->organization = new Organization();
    }

    public function render()
    {
        return view('livewire.add-engagement-connector')
            ->layout('layouts.app-medium');
    }

    public function inviteIndividual()
    {
        $validated = $this->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('invitations')->where(function ($query) {
                    return $query->where([
                        ['invitationable_type', 'App\Models\Engagement'],
                        ['invitationable_id', $this->engagement->id],
                    ]);
                }),
            ],
        ]);

        $validated['role'] = 'connector';

        $invitation = $this->engagement->invitations()->create($validated);

        Mail::to($validated['email'])->send(new ContractorInvitation($invitation));

        flash(__('invitation.create_invitation_succeeded'), 'success');

        return redirect(localized_route('engagements.manage-connector', $this->engagement));
    }

    public function inviteOrganization()
    {
        $validated = $this->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('invitations')->where(function ($query) {
                    return $query->where([
                        ['invitationable_type', 'App\Models\Engagement'],
                        ['invitationable_id', $this->engagement->id],
                    ]);
                }),
            ],
        ]);
    }
}
