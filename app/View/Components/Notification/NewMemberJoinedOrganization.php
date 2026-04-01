<?php

namespace App\View\Components\Notification;

use App\Enums\TeamRole;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class NewMemberJoinedOrganization extends Notification
{
    public function __construct(DatabaseNotification $notification)
    {
        $organizationType = $notification->data['organization_type'];
        $organization = $organizationType::find($notification->data['organization_id']);

        $this->title = __('New member joined organization');
        $this->body = __(':name has joined :organization as :role.', [
            'name' => $notification->data['member_name'],
            'organization' => $organization->getTranslation('name', locale()),
            'role' => TeamRole::labels()[$notification->data['team_role']],
        ]);
        $this->interpretation = __('New member joined organization', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.new-member-joined-organization', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
