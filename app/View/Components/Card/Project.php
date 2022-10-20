<?php

namespace App\View\Components\Card;

use App\Models\Project as ProjectModel;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Project extends Component
{
    public ProjectModel $model;

    public int $level;

    public bool $showRegulatedOrganization;

    public function __construct(ProjectModel $model, int $level = 3, bool $showRegulatedOrganization = true)
    {
        $this->model = $model;
        $this->level = (int) $level;
        $this->showRegulatedOrganization = $showRegulatedOrganization;
    }

    public function render(): View
    {
        return view('components.card.project', [
            'model' => $this->model,
            'level' => $this->level,
            'showRegulatedOrganization' => $this->showRegulatedOrganization,
        ]);
    }
}
