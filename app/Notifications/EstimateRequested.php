<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstimateRequested extends Notification
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
            ->subject(__('New Estimate Request from :projectable', ['projectable' => $this->project->projectable->name]))
            ->markdown(
                'mail.estimate-requested',
                [
                    'url' => localized_route('admin.estimates-and-agreements'),
                    'project' => $this->project,
                ]
            );
    }

    public function toArray(User $notifiable): array
    {
        return [
            'url' => localized_route('admin.estimates-and-agreements'),
            'project_id' => $this->project->id,
        ];
    }
}
