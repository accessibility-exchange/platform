<?php

namespace Tests\RequestFactories;

use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use Worksome\RequestFactories\RequestFactory;

class MeetingRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'title' => ['en' => $this->faker->words(3, true)],
            'date' => now()->addMonth(),
            'start_time' => '9:00',
            'end_time' => '13:00',
            'timezone' => $this->faker->timezone(),
        ];
    }

    public function inPerson(): static
    {
        return $this->state([
            'meeting_types' => [MeetingType::InPerson->value],
            'street_address' => $this->faker->streetAddress(),
            'unit_suite_floor' => $this->faker->boolean(50) ? $this->faker->secondaryAddress() : null,
            'locality' => $this->faker->city(),
            'region' => $this->faker->randomElement(ProvinceOrTerritory::class)->value,
            'postal_code' => $this->faker->regexify('[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJ-NPRSTV-Z] ?\d[ABCEGHJ-NPRSTV-Z]\d'),
            'directions' => $this->faker->boolean(50) ? ['en' => $this->faker->paragraph()] : null,
        ]);
    }

    public function phone(): static
    {
        return $this->state([
            'meeting_types' => [MeetingType::Phone->value],
            'meeting_phone' => '1 (888) 867-0053',
            'additional_phone_information' => $this->faker->boolean(50) ? ['en' => $this->faker->paragraph()] : null,
        ]);
    }

    public function webConference(): static
    {
        return $this->state([
            'meeting_types' => [MeetingType::WebConference->value],
            'meeting_software' => $this->faker->word(),
            'alternative_meeting_software' => $this->faker->boolean(50) ? $this->faker->boolean(50) : null,
            'meeting_url' => $this->faker->url(),
            'additional_video_information' => $this->faker->boolean(50) ? ['en' => $this->faker->paragraph()] : null,
        ]);
    }
}
