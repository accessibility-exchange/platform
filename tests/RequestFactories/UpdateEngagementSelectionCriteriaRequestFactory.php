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
            'ideal_participants' => $this->faker->numberBetween(config('engagement.minimum_participants_floor'), 50),
            'minimum_participants' => function (array $attributes) {
                return $this->faker->numberBetween(config('engagement.minimum_participants_floor'), $attributes['ideal_participants']);
            },
        ];
    }
}
