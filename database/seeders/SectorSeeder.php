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
            'transportation',
            'financial-services',
            'telecommunications',
            'broadcasting',
            'government-programs-and-services',
            'crown-corporations',
        ];

        foreach ($sectors as $sector) {
            Sector::firstOrCreate([
                'name' => [
                    'en' => __('sector.' . $sector . '.name'),
                    'fr' => trans('sector.' . $sector . '.name', [], 'fr'),
                ],
                'description' => [
                    'en' => __('sector.' . $sector . '.description'),
                    'fr' => trans('sector.' . $sector . '.description', [], 'fr'),
                ],
            ]);
        }
    }
}
