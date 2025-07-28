<?php

namespace Database\Factories;

use App\Enums\ProvinceOrTerritory;
use App\Enums\RegulatedOrganizationType;
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
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(RegulatedOrganizationType::class)->value,
            'languages' => config('locales.supported'),
            'about' => ['en' => 'About this regulated organization.'],
            'service_areas' => [ProvinceOrTerritory::NovaScotia->value],
            'contact_person_email' => $this->faker->email,
            'contact_person_name' => $this->faker->name,
            'oriented_at' => now(),
            'validated_at' => now(),
        ];
    }
}
