<?php

namespace Database\Factories;

use App\Models\RegulatedOrganization;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegulatedOrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RegulatedOrganization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'locality' => $this->faker->city(),
            'region' => $this->faker->provinceAbbr(),
        ];
    }
}
