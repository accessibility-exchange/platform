<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class AgreementReceived extends PlatformNotification
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
            ->subject(__('Your agreement has been received'))
            ->markdown(
                'mail.agreement-received',
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
                    'Your agreement has been received for :project. You can now publish your project page and engagement details. Sign in to your account at https://accessibilityexchange.ca to continue.',
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
