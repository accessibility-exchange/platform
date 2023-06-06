<?php

namespace App\View\Components\Notification;

use App\Models\Project;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class AgreementReceived extends Notification
{
    public Project $project;

    public function __construct(DatabaseNotification $notification)
    {
        $this->project = Project::find($notification->data['project_id']);
        $this->title = __('Your agreement has been received');
        $this->body = safe_markdown('Your agreement has been received for **:project**. You can now publish your project page and engagement details.', [
            'project' => $this->project->getTranslation('name', locale()),
        ]);

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.agreement-received', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'project' => $this->project,
        ]);
    }
}
