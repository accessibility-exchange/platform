<?php

namespace App\View\Components\Notification;

use App\Models\Engagement;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class OrganizationRemovedFromEngagement extends Notification
{
    public Engagement $engagement;

    public function __construct(DatabaseNotification $notification)
    {
        $this->engagement = Engagement::find($notification->data['engagement_id']);
        $this->title = __('Your organization has been removed from an engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]);
        $this->body = __('Your organization has been removed from the engagement “:engagement”', ['engagement' => $this->engagement->getTranslation('name', locale())]).'.';

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.organization-removed-from-engagement', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'engagement' => $this->engagement,
        ]);
    }
}
