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
            'Transportation',
            'Financial services',
            'Telecommunications',
            'Radio and television broadcasting',
            'Federal government benefit programs and services',
            'Crown corporations',
        ];

        foreach ($sectors as $sector) {
            Sector::firstOrCreate([
                'name' => $sector,
            ]);
        }
    }
}
