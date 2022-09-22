<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\Component;

class Notification extends Component
{
    public DatabaseNotification $notification;

    public string $title = '';

    public string $body = '';

    public function __construct(DatabaseNotification $notification)
    {
        $this->notification = $notification;
    }

    public function render(): View
    {
        return view('components.notification', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
        ]);
    }
}
