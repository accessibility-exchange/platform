<?php

namespace App\Notifications;

use App\Models\Engagement;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class ParticipantAccepted extends PlatformNotification
{
    public Engagement $engagement;

    public Project $project;

    public function __construct(Engagement $engagement)
    {
        $this->engagement = $engagement;
        $this->project = $engagement->project;
    }

    public function toMail(Project|Organization|User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(
                $notifiable instanceof Project ?
                    __('1 new person accepted their invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]) :
                    __('1 new person accepted your invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())])
            )
            ->line(
                $notifiable instanceof Project ?
                    __('1 new person accepted their invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]) :
                    __('1 new person accepted your invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]).'.'
            )
            ->action(__('Manage participants for this engagement'), localized_route('engagements.manage-participants', $this->engagement));
    }

    public function toVonage(Project|Organization|User $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                $notifiable instanceof Project ?
                    __('1 new person accepted their invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]) :
                    __('1 new person accepted your invitation for :engagement', ['engagement' => $this->engagement->getTranslation('name', locale())]).'. '.__(
                        'Manage participants for this engagement at :url.',
                        [
                            'url' => localized_route('engagements.manage-participants', $this->engagement),
                        ]
                    )
            )
            ->unicode();
    }

    public function toArray(Project|Organization|User $notifiable): array
    {
        return [
            'engagement_id' => $this->engagement->id,
        ];
    }
}
