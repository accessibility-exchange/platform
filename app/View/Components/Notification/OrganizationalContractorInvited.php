<?php

namespace App\View\Components\Notification;

use App\Enums\OrganizationRole;
use App\Models\Invitation;
use App\Models\Project;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class OrganizationalContractorInvited extends Notification
{
    public Invitation $invitation;

    public mixed $invitationable;

    public mixed $projectable;

    public function __construct(DatabaseNotification $notification)
    {
        $this->invitation = Invitation::find($notification->data['invitation_id']);
        $this->invitationable = $this->invitation->invitationable;
        /** @var Project */
        $project = class_basename($this->invitationable) === 'Project' ? $this->invitationable : $this->invitationable->project;
        $this->projectable = $project->projectable;
        $this->title = __('Your organization has been invited as a :role', ['role' => OrganizationRole::labels()[$this->invitation->role]]);
        $this->body = __(
            'Your organization has been invited as a :role to :projectable’s :invitationable_type, :invitationable.',
            [
                'role' => OrganizationRole::labels()[$this->invitation->role],
                'projectable' => $this->projectable->getTranslation('name', locale()),
                'invitationable_type' => $this->invitationable->getAttribute('singular_name'),
                'invitationable' => $this->invitationable->getTranslation('name', locale()),
            ]
        );
        $this->interpretation = __('Your organization has been invited', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.organizational-contractor-invited', [
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
