<?php

namespace Database\Seeders;

use App\Models\AreaType;
use Illuminate\Database\Seeder;

class AreaTypeSeeder extends Seeder
{
    public function run(): void
    {
        $areaTypes = [
            [
                'name' => __('Urban'),
            ],
            [
                'name' => __('Rural'),
            ],
            [
                'name' => __('Remote'),
            ],
        ];

        foreach ($areaTypes as $areaType) {
            AreaType::firstOrCreate([
                'name->en' => $areaType['name'],
                'name->fr' => trans($areaType['name'], [], 'fr'),
                'description->en' => $areaType['description'] ?? null,
                'description->fr' => isset($areaType['description']) ? trans($areaType['description'], [], 'fr') : null,
            ]);
        }
    }
}
