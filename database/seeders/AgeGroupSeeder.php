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
                'name' => __('Children (under 15)'),
            ],
            [
                'name' => __('Youth (15–30)'),
            ],
            [
                'name' => __('Working age adults (15–64)'),
            ],
            [
                'name' => __('Older people (65+)'),
            ],
        ];

        foreach ($ageGroups as $ageGroup) {
            AgeGroup::firstOrCreate([
                'name->en' => $ageGroup['name'],
                'name->fr' => trans($ageGroup['name'], [], 'fr'),
            ]);
        }
    }
}
