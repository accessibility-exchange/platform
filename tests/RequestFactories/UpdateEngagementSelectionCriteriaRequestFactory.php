<?php

namespace Tests\RequestFactories;

use App\Enums\LocationType;
use App\Enums\ProvinceOrTerritory;
use Worksome\RequestFactories\RequestFactory;

class UpdateEngagementSelectionCriteriaRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'location_type' => LocationType::Regions->value,
            'regions' => $this->faker->randomElements(ProvinceOrTerritory::class, null),
            'cross_disability_and_deaf' => true,
            'intersectional' => true,
            'ideal_participants' => $this->faker->numberBetween(10, 50),
            'minimum_participants' => function (array $attributes) {
                return $this->faker->numberBetween(10, $attributes['ideal_participants']);
            },
        ];
    }
}
