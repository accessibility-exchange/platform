<?php

namespace App\View\Components\Notification;

use App\Models\Engagement;
use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class EngagementAdded extends Notification
{
    public Engagement $engagement;

    public function __construct(DatabaseNotification $notification)
    {
        $this->engagement = Engagement::find($notification->data['engagement_id']);
        /** @var Organization|RegulatedOrganization */
        $projectable = $this->engagement->project->projectable;
        $this->title = __('New engagement added');
        $this->body = safe_markdown('A new engagement has been uploaded: [:engagement](:engagement_url) by [:projectable](:projectable_url)', [
            'engagement' => $this->engagement->getTranslation('name', locale()),
            'engagement_url' => localized_route('engagements.show', $this->engagement),
            'projectable' => $projectable->getTranslation('name', locale()),
            'projectable_url' => localized_route($projectable->getRoutePrefix().'.show', $projectable),
        ]);
        $this->interpretation = __('New engagement added', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.engagement-added', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'engagement' => $this->engagement,
            'interpretation' => $this->interpretation,
        ]);
    }
}
