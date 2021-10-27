<?php

namespace Database\Seeders;

use App\Models\ConsultingMethod;
use Illuminate\Database\Seeder;

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
            ConsultingMethod::firstOrCreate([
                'name->en' => $method,
            ]);
        }
    }
}
