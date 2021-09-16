<?php

namespace App\States;

class Published extends PublicationState
{
    public static $name = 'published';

    public function slug(): string
    {
        return 'published';
    }
}
