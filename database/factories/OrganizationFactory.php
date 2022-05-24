<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

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

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Organization $organization) {
            $organization->users()->attach(User::factory()->create(['context' => 'regulated-organization']), ['role' => 'admin']);
        });
    }
}
