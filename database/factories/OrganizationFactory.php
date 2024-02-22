<?php

namespace Database\Factories;

use App\Enums\OrganizationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(OrganizationType::class)->value,
            'languages' => config('locales.supported'),
            'roles' => [],
            'service_areas' => ['NS'],
            'working_languages' => ['en', 'fr'],
            'contact_person_email' => $this->faker->email(),
            'oriented_at' => now(),
            'validated_at' => now(),
        ];
    }
}
