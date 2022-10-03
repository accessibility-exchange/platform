<?php

namespace Database\Factories;

use App\Enums\TimeZone;
use App\Models\Engagement;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'engagement_id' => Engagement::factory(),
            'title' => 'Meeting 1',
            'date' => $this->faker->dateTimeBetween('+1 months', '+6 months'),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'timezone' => $this->faker->randomElement(array_column(TimeZone::cases(), 'value')),
            'meeting_types' => ['phone'],
            'meeting_phone' => '9024144567',
        ];
    }
}
