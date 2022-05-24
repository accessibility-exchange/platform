<?php

namespace Database\Factories;

use App\Models\RegulatedOrganization;
use App\Models\User;
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
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (RegulatedOrganization $regulatedOrganization) {
            $regulatedOrganization->users()->attach(User::factory()->create(['context' => 'regulated-organization']), ['role' => 'admin']);
        });
    }
}
