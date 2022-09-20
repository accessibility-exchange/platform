<?php

namespace App\Notifications;

use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstimateRequested extends Notification
{
    use Queueable;

    public Project $project;

    public Organization|RegulatedOrganization $projectable;

    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->projectable = $this->project->projectable;
    }

    public function via(User $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Estimate Request from :projectable', ['projectable' => $this->projectable->getTranslation('name', locale())]))
            ->markdown(
                'mail.estimate-requested',
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
