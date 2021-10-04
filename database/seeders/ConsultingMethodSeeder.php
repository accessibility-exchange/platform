<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsultingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
            'Interviews',
            'Surveys',
            'Focus groups',
            'Workshops',
            'Other',
        ];

        foreach ($methods as $method) {
            DB::table('consulting_methods')->insert([
                'name' => json_encode(['en' => $method]),
            ]);
        }
    }
}
