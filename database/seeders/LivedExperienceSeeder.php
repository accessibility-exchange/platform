<?php

namespace Database\Seeders;

use App\Models\LivedExperience;
use Illuminate\Database\Seeder;

class LivedExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $experiences = [
            __('People who experience disabilities'),
            __('Deaf people'),
            __('Supporters of people who experience disabilities and/or Deaf people'),
        ];

        foreach ($experiences as $experience) {
            LivedExperience::firstOrCreate([
                'name->en' => $experience,
                'name->fr' => trans($experience, [], 'fr'),
            ]);
        }
    }
}
