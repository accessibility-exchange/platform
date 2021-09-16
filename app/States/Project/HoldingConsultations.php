<?php

namespace App\States\Project;

class HoldingConsultations extends ProjectState
{
    public static $name = 'holding_consultations';

    public function slug(): string
    {
        return 'holding_consultations';
    }

    public function label(): string
    {
        return __('Holding consultations');
    }
}
