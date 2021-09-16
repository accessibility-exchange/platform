<?php

namespace App\States\Project;

class Preparing extends ProjectState
{
    public static $name = 'preparing';

    public function slug(): string
    {
        return 'preparing';
    }

    public function label(): string
    {
        return __('Preparing for consultations');
    }
}
