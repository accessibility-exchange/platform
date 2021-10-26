<?php

namespace Database\Seeders;

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
            DB::table('phases')->insert([
                'name' => json_encode(['en' => $phase]),
            ]);
        }
    }
}
