<?php

namespace Database\Seeders;

use App\Models\Phase;
use Illuminate\Database\Seeder;

class PhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phases = [
            'Preparing for consultation',
            'Going through consultation',
            'After consultation and preparing reports',
        ];

        foreach ($phases as $phase) {
            Phase::firstOrCreate([
                'name->en' => $phase,
            ]);
        }
    }
}
