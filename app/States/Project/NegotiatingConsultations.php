<?php

namespace App\States\Project;

class NegotiatingConsultations extends ProjectState
{
    public static $name = 'negotiating_consultations';

    public function slug(): string
    {
        return 'negotiating_consultations';
    }

    public function label(): string
    {
        return __('Learning how to work together');
    }
}
