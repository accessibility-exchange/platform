<?php

namespace App\Observers;

use App\Enums\ProvinceOrTerritory;
use App\Models\Engagement;

class EngagementObserver
{
    public function created(Engagement $engagement): void
    {
        $engagement->matchingStrategy()->create([
            'regions' => array_column(ProvinceOrTerritory::cases(), 'value'),
        ]);
    }
}
