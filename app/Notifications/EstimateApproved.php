<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstimateApproved extends Notification
{
    use Queueable;

    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via(User $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Estimate Approval from :projectable', ['projectable' => $this->project->projectable->name]))
            ->markdown(
                'mail.estimate-approved',
                [
                    'project' => $this->project,
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
