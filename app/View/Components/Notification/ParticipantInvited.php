<?php

namespace App\View\Components\Notification;

use App\Models\Engagement;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\HtmlString;

class ParticipantInvited extends Notification
{
    public Invitation $invitation;

    public Engagement $invitationable;

    public function __construct(DatabaseNotification $notification)
    {
        $this->invitation = Invitation::find($notification->data['invitation_id']);
        $this->invitationable = $this->invitation->invitationable;
        /** @var Project */
        $project = $this->invitationable->project;
        /** @var Organization|RegulatedOrganization */
        $projectable = $project->projectable;
        $this->title = __('You have been invited as a Consultation Participant');
        $this->body = new HtmlString(
            safe_markdown(
                'You’ve been invited to participate in [:projectable](:projectable_url)’s project, [:project](:project_url). They would like you to join them for their engagement, [:engagement](:engagement_url).',
                [
                    'projectable' => $projectable->getTranslation('name', locale()),
                    'projectable_url' => localized_route($projectable->getRoutePrefix().'.show', $projectable),
                    'project' => $project->getTranslation('name', locale()),
                    'project_url' => localized_route('projects.show', $this->invitationable->project),
                    'engagement' => $this->invitationable->getTranslation('name', locale()),
                    'engagement_url' => localized_route('engagements.show', $this->invitationable),
                ]
            )
            ."\n\n"
            .safe_markdown('**Please respond by :signup_by_date.**', ['signup_by_date' => $this->invitationable->signup_by_date->isoFormat('LL')])
        );
        $this->interpretation = __('You have been invited as a Consultation Participant', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.participant-invited', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'invitationable' => $this->invitationable,
            'invitation' => $this->invitation,
            'interpretation' => $this->interpretation,
        ]);
    }
}
