<?php

namespace App\Observers;

use App\Models\Engagement;

class EngagementObserver
{
    public function created(Engagement $engagement): void
    {
        $engagement->matchingStrategy()->create();
    }
}
