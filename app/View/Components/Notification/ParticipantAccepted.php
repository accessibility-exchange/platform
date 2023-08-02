<?php

namespace App\View\Components\Notification;

use App\Models\Engagement;
use App\Models\Project;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class ParticipantAccepted extends Notification
{
    public Engagement $engagement;

    public function __construct(DatabaseNotification $notification)
    {
        $this->engagement = Engagement::find($notification->data['engagement_id']);
        $this->title = $notification->notifiable instanceof Project ?
            __('1 new person accepted their invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]) :
            __('1 new person accepted your invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]);
        $this->body = __('Manage participants for this engagement:');
        $this->interpretation = $notification->notifiable instanceof Project ?
            __('1 new person accepted their invitation for an engagement', [], 'en') :
            __('1 new person accepted your invitation for an engagement', [], 'en');

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
            'interpretation' => $this->interpretation,
        ]);
    }
}
