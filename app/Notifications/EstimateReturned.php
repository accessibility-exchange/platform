<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class EstimateReturned extends PlatformNotification
{
    public Project $project;

    public mixed $projectable;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function toMail(Project $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your estimate has been returned'))
            ->markdown(
                'mail.estimate-returned',
                [
                    'project' => $this->project,
                ]
            );
    }

    public function toVonage(Project $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content(
                __(
                    'Your estimates has been returned for :project, along with a project agreement for you to sign. Sign in to your account at https://accessibilityexchange.ca for further details.',
                    [
                        'project' => $this->project->getTranslation('name', locale()),
                    ]
                )
            )
            ->unicode();
    }

    public function toArray(Project $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
        ];
    }
}
