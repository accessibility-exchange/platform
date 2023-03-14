<?php

namespace App\View\Components\Notification;

use App\Models\Engagement;
use App\Models\Individual;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class AccessNeedsFacilitationRequested extends Notification
{
    public Individual $individual;

    public Engagement $engagement;

    public function __construct(DatabaseNotification $notification)
    {
        $this->individual = Individual::find($notification->data['individual_id']);
        $this->engagement = Engagement::find($notification->data['engagement_id']);
        $this->title = __(':name requires access needs facilitation', ['name' => $this->individual->name]);
        $this->body = __(
            'Please contact :name to facilitate their access needs being met on the engagement [:engagement_name](:engagement_url).',
            // Following array throws errors because it thinks the localized engagement name might be an array
            // @phpstan-ignore-next-line
            [
                'name' => $this->individual->name,
                'engagement_name' => $this->engagement->name,
                'engagement_url' => localized_route('engagements.show', $this->engagement),
            ]
        );
        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.access-needs-facilitation-requested', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'individual' => $this->individual,
        ]);
    }
}
