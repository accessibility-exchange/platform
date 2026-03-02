<?php

namespace App\View\Components\Notification;

use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class NewOrganizationRegistered extends Notification
{
    public function __construct(DatabaseNotification $notification)
    {
        $this->title = $notification->data['title'];
        $this->body = $notification->data['body'];
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
