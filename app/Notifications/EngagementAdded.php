<?php

namespace App\Notifications;

use App\Models\Engagement;
use Illuminate\Notifications\Messages\MailMessage;

class EngagementAdded extends PlatformNotification
{
    public Engagement $engagement;

    public mixed $projectable;

    public function __construct(Engagement $engagement)
    {
        $this->engagement = $engagement;
        $this->projectable = $this->engagement->project->projectable;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Engagement from :projectable', ['projectable' => $this->projectable->getTranslation('name', locale())]))
            ->markdown(
                'mail.engagement-added',
                [
                    'engagement' => $this->engagement,
                    'projectable' => $this->projectable,
                ]
            );
    }

    public function toArray(): array
    {
        return [
            'engagement_id' => $this->engagement->id,
        ];
    }
}
