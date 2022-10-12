<?php

namespace App\View\Components\Notification;

use App\Models\Engagement;
use App\Models\Project;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class ParticipantDeclined extends Notification
{
    public Engagement $engagement;

    public function __construct(DatabaseNotification $notification)
    {
        $this->engagement = Engagement::find($notification->data['engagement_id']);
        $this->title = $notification->notifiable instanceof Project ?
            __('1 person declined their invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]) :
            __('1 person declined your invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]);
        $this->body = __('Manage participants for this engagement:');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.participant-declined', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'engagement' => $this->engagement,
        ]);
    }
}
