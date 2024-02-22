<?php

namespace Tests\RequestFactories;

use App\Models\Impact;
use Carbon\Carbon;
use Worksome\RequestFactories\RequestFactory;

class UpdateProjectRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => ['en' => 'Test project - '.$this->faker->words(3, true)],
            'goals' => ['en' => 'Test goals'],
            'scope' => ['en' => 'Test scope'],
            'regions' => ['ON', 'BC'],
            'impacts' => [Impact::first()->id],
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now()->addYear(),
            'outcome_analysis' => ['internal'],
            'outcomes' => ['en' => 'Test report'],
            'public_outcomes' => true,
            'save' => __('Save'),
        ];
    }
}
