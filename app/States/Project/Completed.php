<?php

namespace App\States\Project;

class Completed extends ProjectState
{
    public static $name = 'completed';

    public function slug(): string
    {
        return 'completed';
    }

    public function label(): string
    {
        return __('Completed');
    }
}
