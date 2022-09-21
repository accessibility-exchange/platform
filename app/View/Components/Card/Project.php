<?php

namespace App\View\Components\Card;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Project extends Component
{
    public Project $project;

    public int $level;

    public bool $showRegulatedOrganization;

    public function __construct(Project $project, int $level = 3, bool $showRegulatedOrganization = true)
    {
        $this->project = $project;
        $this->level = (int) $level;
        $this->showRegulatedOrganization = $showRegulatedOrganization;
    }

    public function render(): View
    {
        return view('components.card.project', [
            'project' => $this->project,
            'level' => $this->level,
            'showRegulatedOrganization' => $this->showRegulatedOrganization,
        ]);
    }
}
