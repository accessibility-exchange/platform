<?php

namespace App\View\Components\Notification;

use App\Enums\UserContext;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class NewUserRegistered extends Notification
{
    public function __construct(DatabaseNotification $notification)
    {
        $this->title = __('New user registered');
        $this->body = __('A new user, :name, has registered as :context.', [
            'name' => $notification->data['user_name'],
            'context' => UserContext::labels()[$notification->data['user_context']],
        ]);
        $this->interpretation = __('New user registered', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.new-user-registered', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'interpretation' => $this->interpretation,
        ]);
    }
}
