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
            __('Interviews'),
            __('Surveys'),
            __('Focus groups'),
            __('Workshops'),
            __('Other'),
        ];

        foreach ($methods as $method) {
            ConsultingMethod::firstOrCreate([
                'name->en' => $method,
                'name->fr' => trans($method, [], 'fr'),
            ]);
        }
    }
}
