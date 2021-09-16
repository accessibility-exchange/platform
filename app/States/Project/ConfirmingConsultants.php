<?php

namespace App\States\Project;

class ConfirmingConsultants extends ProjectState
{
    public static $name = 'confirming_consultants';

    public function slug(): string
    {
        return 'confirming_consultants';
    }

    public function label(): string
    {
        return __('Building consulting team');
    }
}
