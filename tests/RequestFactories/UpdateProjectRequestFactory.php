<?php

namespace Tests\RequestFactories;

use App\Enums\OutcomeAnalyzer;
use App\Enums\ProvinceOrTerritory;
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
            'regions' => [ProvinceOrTerritory::Ontario->value, ProvinceOrTerritory::BritishColumbia->value],
            'impacts' => [Impact::first()->id],
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now()->addYear(),
            'outcome_analysis' => [OutcomeAnalyzer::Internal->value],
            'outcomes' => ['en' => 'Test report'],
            'public_outcomes' => true,
            'save' => __('Save'),
        ];
    }
}
