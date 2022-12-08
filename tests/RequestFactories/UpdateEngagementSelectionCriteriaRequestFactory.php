<?php

namespace Tests\RequestFactories;

use App\Enums\ProvinceOrTerritory;
use Worksome\RequestFactories\RequestFactory;

class UpdateEngagementSelectionCriteriaRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'location_type' => 'regions',
            'regions' => array_column(ProvinceOrTerritory::cases(), 'value'),
            'cross_disability_and_deaf' => 1,
            'intersectional' => 1,
            'ideal_participants' => 25,
            'minimum_participants' => 15,
        ];
    }
}
