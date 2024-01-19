<?php

namespace Tests\RequestFactories;

use Carbon\Carbon;
use Worksome\RequestFactories\RequestFactory;

class UpdateProjectTeamRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'team_trainings' => [
                [
                    'name' => 'Example Training - '.$this->faker->words(3, true),
                    'date' => Carbon::now()->addMonth(),
                    'trainer_name' => "{$this->faker->company()} {$this->faker->companySuffix()}",
                    'trainer_url' => 'example.com',
                ],
            ],
            'contact_person_email' => 'me@here.com',
            'contact_person_name' => $this->faker->name(),
            'contact_person_phone' => phone('416-555-5555', 'CA')->formatForCountry('CA'),
            'preferred_contact_method' => 'email',
            'contact_person_response_time' => ['en' => 'ASAP'],
        ];
    }
}
