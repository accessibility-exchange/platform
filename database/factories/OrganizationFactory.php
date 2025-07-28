<?php

namespace Database\Factories;

use App\Enums\OrganizationType;
use App\Enums\ProvinceOrTerritory;
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
            'about' => ['en' => 'About this organization.'],
            'service_areas' => [ProvinceOrTerritory::NovaScotia->value],
            'working_languages' => ['en', 'fr'],
            'contact_person_email' => $this->faker->email(),
            'oriented_at' => now(),
            'validated_at' => now(),
            'notification_settings' => ['engagements' => '1'],
        ];
    }
}
