<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sectors = [
            'transportation' => [
                'name' => __('Transportation'),
                'description' => __('Trains, airplanes, and buses'),
            ],
            'financial-services' => [
                'name' => __('Financial services'),
                'description' => __('Banks and credit unions'),
            ],
            'telecommunications' => [
                'name' => __('Telecommunications'),
                'description' => __('Phone and internet providers'),
            ],
            'broadcasting' => [
                'name' => __('Broadcasting'),
                'description' => __('Radio and television'),
            ],
            'government-programs-and-services' => [
                'name' => __('Federal government programs and services'),
                'description' => __('For example: the Canada Revenue Agency, the Immigration and Refugee Board of Canada, and Service Canada'),
            ],
            'crown-corporations' => [
                'name' => __('Crown corporations'),
                'description' => __('For example: Canada Post, the Canada Council for the Arts'),
            ],
        ];

        foreach ($sectors as $sector) {
            Sector::firstOrCreate([
                'name' => [
                    'en' => $sector['name'],
                    'fr' => trans($sector['name'], [], 'fr'),
                ],
                'description' => [
                    'en' => $sector['description'],
                    'fr' => trans($sector['description'], [], 'fr'),
                ],
            ]);
        }
    }
}
