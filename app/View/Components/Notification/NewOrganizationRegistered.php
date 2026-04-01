<?php

namespace App\View\Components\Notification;

use App\Enums\UserContext;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class NewOrganizationRegistered extends Notification
{
    public function __construct(DatabaseNotification $notification)
    {
        $organizationType = $notification->data['organization_type'];
        $organization = $organizationType::find($notification->data['organization_id']);

        $this->title = __('New organization registered');
        $this->body = __('A new organization, :organization, has been registered by :name as :context.', [
            'organization' => $organization->getTranslation('name', locale()),
            'name' => $notification->data['creator_name'],
            'context' => UserContext::labels()[$notification->data['user_context']],
        ]);
        $this->interpretation = __('New organization registered', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.new-organization-registered', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
