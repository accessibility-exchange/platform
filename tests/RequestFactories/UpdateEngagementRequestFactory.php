<?php

namespace Tests\RequestFactories;

use App\Enums\AcceptedFormat;
use App\Enums\Availability;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use Worksome\RequestFactories\RequestFactory;

class UpdateEngagementRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => ['en' => 'Workshop'],
            'description' => ['en' => $this->faker->paragraph()],
            'signup_by_date' => now()->addMonth(1),
            'paid' => $this->faker->boolean(50),
        ];
    }

    public function meetingInPerson(): static
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

    public function meetingWebConference(): static
    {
        return $this->state([
            'meeting_types' => [MeetingType::WebConference->value],
            'meeting_software' => $this->faker->word(),
            'alternative_meeting_software' => $this->faker->boolean(50) ? $this->faker->boolean(50) : null,
            'meeting_url' => $this->faker->url(),
            'additional_video_information' => $this->faker->boolean(50) ? ['en' => $this->faker->paragraph()] : null,
        ]);
    }

    public function meetingPhone(): static
    {
        return $this->state([
            'meeting_types' => [MeetingType::Phone->value],
            'meeting_phone' => '1 (888) 867-0053',
            'additional_phone_information' => $this->faker->boolean(50) ? ['en' => $this->faker->paragraph()] : null,
        ]);
    }

    public function formatInterview(): static
    {
        return $this->state([
            'window_start_date' => now()->addMonth(3),
            'window_end_date' => now()->addMonths(6),
            'window_start_time' => '9:00',
            'window_end_time' => '13:00',
            'timezone' => $this->faker->timezone(),
            'window_flexibility' => $this->faker->boolean(50) ? $this->faker->boolean(50) : null,
            'weekday_availabilities' => [
                'monday' => $this->faker->randomElement(Availability::class)->value,
                'tuesday' => $this->faker->randomElement(Availability::class)->value,
                'wednesday' => $this->faker->randomElement(Availability::class)->value,
                'thursday' => $this->faker->randomElement(Availability::class)->value,
                'friday' => $this->faker->randomElement(Availability::class)->value,
                'saturday' => $this->faker->randomElement(Availability::class)->value,
                'sunday' => $this->faker->randomElement(Availability::class)->value,
            ],
            'materials_by_date' => now()->addMonth(3),
            'complete_by_date' => now()->addMonth(6),
            'accepted_formats' => function (array $attributes) {
                return empty($attributes['other_accepted_formats']) || $this->faker->boolean(50) ? $this->faker->randomElements(AcceptedFormat::class, null) : null;
            },
            'other_accepted_format' => function (array $attributes) {
                return $attributes['other_accepted_formats'] ? ['en' => $this->faker->word()] : null;
            },
            'other_accepted_format' => ['en' => $this->faker->word()],
            'open_to_other_formats' => $this->faker->boolean(50) ? $this->faker->boolean(50) : null,
        ]);
    }

    public function formatAsync(): static
    {
        return $this->state([
            'materials_by_date' => now()->addMonth(3),
            'complete_by_date' => now()->addMonth(6),
            'document_languages' => $this->faker->randomElements(array_keys(get_available_languages(true)), 10),
        ]);
    }
}
