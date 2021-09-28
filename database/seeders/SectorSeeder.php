<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            DB::table('sectors')->insert([
                'name' => json_encode(['en' => $sector]),
            ]);
        }
    }
}
