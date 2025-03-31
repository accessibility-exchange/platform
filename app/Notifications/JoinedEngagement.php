<?php

namespace App\Notifications;

use App\Models\Engagement;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class JoinedEngagement extends PlatformNotification
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
            ->subject(__('Signed up for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]))
            ->markdown(
                'mail.joined-engagement',
                [
                    'engagement' => $this->engagement,
                    'projectable' => $this->projectable,
                ]
            );
    }

    public function toVonage(): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __('You have signed up for :engagement by :projectable', [
                    'engagement' => $this->engagement->getTranslation('name', locale()),
                    'projectable' => $this->projectable->getTranslation('name', locale()),
                ]).'. '.__(
                    'View this engagement at :url.',
                    [
                        'url' => localized_route('engagements.show', $this->engagement),
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(): array
    {
        return [
            'engagement_id' => $this->engagement->id,
            'projectable_id' => $this->projectable->id,
        ];
    }
}
