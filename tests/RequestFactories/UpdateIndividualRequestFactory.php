<?php

namespace Tests\RequestFactories;

use App\Enums\ProvinceOrTerritory;
use Worksome\RequestFactories\RequestFactory;

class UpdateIndividualRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'region' => $this->faker->randomElement(ProvinceOrTerritory::class)->value,
            'bio' => ['en' => $this->faker->paragraph()],
        ];
    }
}
