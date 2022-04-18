<?php

namespace Database\Seeders;

use App\Models\AgeGroup;
use Illuminate\Database\Seeder;

class AgeGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ageGroups = [
            [
                'name' => 'Youth (18–24)',
            ],
            [
                'name' => 'Adults (25–64)',
            ],
            [
                'name' => 'Seniors (65+)',
            ],
        ];

        foreach ($ageGroups as $ageGroup) {
            AgeGroup::firstOrCreate([
                'name->en' => $ageGroup['name'],
            ]);
        }
    }
}
