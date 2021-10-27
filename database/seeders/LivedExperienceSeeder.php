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
            'Blind or partially sighted',
            'Mobility-related disability',
            'Limb differences',
            'Cognitive or intellectual disability',
            'Mental health challenges (psychosocial disability)',
            'Neurological disability',
            'Family member of someone with a disability',
            'Support person of someone with a disability',
            'Deaf',
            'Hard of hearing',
            'Chronic conditions',
            'Communication barriers',
            'Invisible disability',
        ];

        foreach ($experiences as $experience) {
            LivedExperience::firstOrCreate([
                'name->en' => $experience,
            ]);
        }
    }
}
