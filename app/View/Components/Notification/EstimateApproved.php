<?php

namespace App\View\Components\Notification;

use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\View\Components\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;

class EstimateApproved extends Notification
{
    public Project $project;

    public mixed $projectable;

    public function __construct(DatabaseNotification $notification)
    {
        $this->project = Project::find($notification->data['project_id']);
        $this->projectable = $this->project->projectable;
        /** @var Organization|RegulatedOrganization */
        $projectable = $this->projectable;
        $this->title = __('New estimate approval');
        $this->body = safe_markdown('[:projectable](:projectable_url) has approved an estimate for their project [:project](:project_url).', [
            'projectable' => $projectable->getTranslation('name', locale()),
            'projectable_url' => localized_route($projectable->getRoutePrefix().'.show', $projectable),
            'project' => $this->project->getTranslation('name', locale()),
            'project_url' => localized_route('projects.show', $this->project),
        ]);
        $this->interpretation = __('New estimate approval', [], 'en');

        parent::__construct($notification);
    }

    public function render(): View
    {
        return view('components.notification.estimate-approved', [
            'notification' => $this->notification,
            'read' => ! is_null($this->notification->read_at),
            'title' => $this->title,
            'body' => $this->body,
            'projectable' => $this->projectable,
            'project' => $this->project,
            'interpretation' => $this->interpretation,
        ]);
    }
}
