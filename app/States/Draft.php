<?php

namespace App\States;

class Draft extends PublicationState
{
    public static $name = 'draft';

    public function slug(): string
    {
        return 'draft';
    }
}
