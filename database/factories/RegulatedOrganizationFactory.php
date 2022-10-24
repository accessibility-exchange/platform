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
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(['government', 'business', 'public-sector']),
            'languages' => ['en', 'fr', 'ase', 'fcs'],
            'service_areas' => ['NS'],
            'contact_person_email' => $this->faker->email,
            'contact_person_name' => $this->faker->name,
        ];
    }
}
