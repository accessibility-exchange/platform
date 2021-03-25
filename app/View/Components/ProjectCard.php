<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProjectCard extends Component
{
    /**
     * The project.
     *
     * @var \App\Models\Project;
     */
    public $project;

    /**
     * The heading level for the card.
     *
     * @var int;
     */
    public $level;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($project, $level = 3)
    {
        $this->project = $project;
        $this->level = (int) $level;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.project-card', ['project' => $this->project, 'level' => $this->level]);
    }
}
