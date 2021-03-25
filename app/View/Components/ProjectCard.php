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
     * Whether the project's parent entity should be shown on the card.
     *
     * @var bool
     */
    public $showEntity;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($project, $level = 3, $showEntity = true)
    {
        $this->project = $project;
        $this->level = (int) $level;
        $this->showEntity = $showEntity;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.project-card', [
            'project' => $this->project,
            'level' => $this->level,
            'showEntity' => $this->showEntity
        ]);
    }
}
