<?php

namespace Tests\RequestFactories;

use App\Enums\AcceptedFormat;
use App\Enums\Availability;
use App\Enums\MeetingType;
use App\Enums\ProvinceOrTerritory;
use App\Models\Meeting;
use Worksome\RequestFactories\RequestFactory;

class UpdateEngagementRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => ['en' => 'Workshop'],
            'description' => ['en' => $this->faker->paragraph()],
            'signup_by_date' => now()->addMonth(1),
            // 'signup_by_date' => $this->faker->dateTimeBetween('+1 months', '+6 months')->format('Y-m-d'),
            'paid' => $this->faker->boolean(50),
        ];
    }

    public function meetingInPerson(): static
    {
        return $this->state([
            // 'meeting_types' => array_unique(array_merge([MeetingType::InPerson->value], $this->faker->randomElements(MeetingType::class, null))),
            'meeting_types' => [MeetingType::InPerson->value],
            'street_address' => $this->faker->streetAddress(),
            'unit_suite_floor' => $this->faker->boolean(50) ? $this->faker->secondaryAddress() : null,
            'locality' => $this->faker->city(),
            'region' => $this->faker->randomElement(ProvinceOrTerritory::class)->value,
            'postal_code' => $this->faker->regexify('[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJ-NPRSTV-Z] ?\d[ABCEGHJ-NPRSTV-Z]\d'),
            // 'postal_code' => $this->faker->postalcode(),
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
            'meeting_phone' => $this->faker->phoneNumber(),
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
            // 'meeting_types' => $this->faker->randomElements(array_filter(array_column(MeetingType::cases(), 'value'), fn ($val) => $val !== MeetingType::InPerson->value), null),
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

/*

public function rules(): array
    {
        $weekdayAvailabilitiesRules = [
            'nullable',
            Rule::excludeIf($this->engagement->format !== 'interviews'),
            Rule::requiredIf($this->engagement->format === 'interviews'),
            new Enum(Availability::class),
        ];

        return [
            'name.*' => 'nullable|string',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'description.*' => 'nullable|string',
            'description.en' => 'required_without:description.fr',
            'description.fr' => 'required_without:description.en',
            'window_start_date' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date',
                'before:window_end_date',
            ],
            'window_end_date' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date',
                'after:window_start_date',
            ],
            'window_start_time' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date_format:G:i',
                'before:window_end_time',
            ],
            'window_end_time' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'date_format:G:i',
                'after:window_start_time',
            ],
            'timezone' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'timezone',
            ],
            'window_flexibility' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                'boolean',
            ],
            'weekday_availabilities' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'array',
            ],
            'weekday_availabilities.monday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.tuesday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.wednesday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.thursday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.friday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.saturday' => $weekdayAvailabilitiesRules,
            'weekday_availabilities.sunday' => $weekdayAvailabilitiesRules,
            'meeting_types' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews'),
                'array',
            ],
            'meeting_types.*' => [
                'nullable',
                new Enum(MeetingType::class),
            ],
            'street_address' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', $this->input('meeting_types', []))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', $this->input('meeting_types', []))),
                'string',
            ],
            'unit_suite_floor' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', $this->input('meeting_types', []))),
                'string',
            ],
            'locality' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', $this->input('meeting_types', []))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', $this->input('meeting_types', []))),
                'string',
            ],
            'region' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', $this->input('meeting_types', []))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', $this->input('meeting_types', []))),
                new Enum(ProvinceOrTerritory::class),
            ],
            'postal_code' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', $this->input('meeting_types', []))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('in_person', $this->input('meeting_types', []))),
                'postal_code:CA',
            ],
            'directions' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('in_person', $this->input('meeting_types', []))),
                'array',
            ],
            'meeting_software' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', $this->input('meeting_types', []))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('web_conference', $this->input('meeting_types', []))),
            ],
            'alternative_meeting_software' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', $this->input('meeting_types', []))),
                'boolean',
            ],
            'meeting_url' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', $this->input('meeting_types', []))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('web_conference', $this->input('meeting_types', []))),
            ],
            'additional_video_information' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('web_conference', $this->input('meeting_types', []))),
                'array',
            ],
            'meeting_phone' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('phone', $this->input('meeting_types', []))),
                Rule::requiredIf($this->engagement->format === 'interviews' && in_array('phone', $this->input('meeting_types', []))),
                'phone:CA',
            ],
            'additional_phone_information' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! in_array('phone', $this->input('meeting_types', []))),
                'array',
            ],
            'materials_by_date' => [
                'nullable',
                Rule::excludeIf(! in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                Rule::requiredIf(in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                'date',
                'before:complete_by_date',
            ],
            'complete_by_date' => [
                'nullable',
                Rule::excludeIf(! in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                Rule::requiredIf(in_array($this->engagement->format, ['interviews', 'survey', 'other-async'])),
                'date',
                'after:materials_by_date',
            ],
            'document_languages' => [
                'nullable',
                Rule::requiredIf(in_array($this->engagement->format, ['survey', 'other-async'])),
                'array',
            ],
            'document_languages.*' => [
                Rule::in(array_keys(get_available_languages(true))),
            ],
            'accepted_formats' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                Rule::requiredIf($this->engagement->format === 'interviews' && ! request('other_accepted_format')),
                'array',
            ],
            'accepted_formats.*' => [
                'nullable',
                new Enum(AcceptedFormat::class),
            ],
            'other_accepted_formats' => [
                'nullable',
                'boolean',
            ],
            'other_accepted_format' => [
                Rule::excludeIf($this->engagement->format !== 'interviews' || ! request('other_accepted_formats')),
            ],
            'other_accepted_format.en' => [
                'nullable',
                'required_without:name.fr',
                'string',
            ],
            'other_accepted_format.fr' => [
                'nullable',
                'required_without:name.en',
                'string',
            ],
            'open_to_other_formats' => [
                'nullable',
                Rule::excludeIf($this->engagement->format !== 'interviews'),
                'boolean',
            ],
            'paid' => [
                'nullable',
                'boolean',
            ],
            'signup_by_date' => [
                'nullable',
                Rule::requiredIf($this->engagement->who === 'individuals'),
                Rule::excludeIf($this->engagement->who === 'organization'),
                'date',
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('other_accepted_format.en', 'required_without:other_accepted_format.fr', function ($input) {
            return $input->other_accepted_formats === false;
        });

        $validator->sometimes('other_accepted_format.fr', 'required_without:other_accepted_format.en', function ($input) {
            return ! $input->other_accepted_formats === false;
        });

        $validator->sometimes('signup_by_date', 'before:window_start_date', function ($input) {
            return ! blank($input->window_start_date);
        });

        $validator->sometimes('signup_by_date', 'before:materials_by_date', function ($input) {
            return ! blank($input->materials_by_date);
        });
    }


*/
