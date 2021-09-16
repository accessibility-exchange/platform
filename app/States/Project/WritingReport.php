<?php

namespace App\States\Project;

class WritingReport extends ProjectState
{
    public static $name = 'writing_report';

    public function slug(): string
    {
        return 'writing_report';
    }

    public function label(): string
    {
        return __('Writing report');
    }
}
