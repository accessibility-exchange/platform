<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProjectCard extends Component
{
    /**
     * The project.
     *
     * @var \App\Models\Project
     */
    public $project;

    /**
     * The heading level for the card.
     *
     * @var int
     */
    public $level;

    /**
     * Whether the project's parent federally regulated organization should be shown on the card.
     *
     * @var bool
     */
    public $showRegulatedOrganization;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($project, $level = 3, $showRegulatedOrganization = true)
    {
        $this->project = $project;
        $this->level = (int) $level;
        $this->showRegulatedOrganization = $showRegulatedOrganization;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.project', [
            'project' => $this->project,
            'level' => $this->level,
            'showRegulatedOrganization' => $this->showRegulatedOrganization,
        ]);
    }
}
