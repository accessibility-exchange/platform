<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;

class EstimateApproved extends PlatformNotification
{
    public Project $project;

    public mixed $projectable;

    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->projectable = $this->project->projectable;
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Estimate Approval from :projectable', ['projectable' => $this->projectable->getTranslation('name', locale())]))
            ->markdown(
                'mail.estimate-approved',
                [
                    'project' => $this->project,
                    'projectable' => $this->projectable,
                ]
            );
    }

    public function toArray(User $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
        ];
    }
}
