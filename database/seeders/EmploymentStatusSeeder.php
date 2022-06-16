<?php

namespace Database\Seeders;

use App\Models\EmploymentStatus;
use Illuminate\Database\Seeder;

class EmploymentStatusSeeder extends Seeder
{
    public function run()
    {
        $employmentStatuses = [
            [
                'name' => __('Employed'),
            ],
            [
                'name' => __('Underemployed'),
            ],
            [
                'name' => __('Unemployed'),
            ],
            [
                'name' => __('Not in the labour force'),
            ],
        ];

        foreach ($employmentStatuses as $status) {
            EmploymentStatus::firstOrCreate([
                'name->en' => $status['name'],
                'name->fr' => trans($status['name'], [], 'fr'),
                'description->en' => $status['description'] ?? null,
                'description->fr' => isset($status['description']) ? trans($status['description'], [], 'fr') : null,
            ]);
        }
    }
}
