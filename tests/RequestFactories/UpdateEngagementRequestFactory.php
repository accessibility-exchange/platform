<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class UpdateEngagementRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => ['en' => 'Workshop'],
            'description' => ['en' => $this->faker->paragraph()],
            'signup_by_date' => $this->faker->dateTimeBetween('+1 months', '+6 months')->format('Y-m-d'),
        ];
    }
}
