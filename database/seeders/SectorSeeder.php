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
            'federal-government' => [
                'name' => __('Government of Canada'),
                'description' => __('Including government departments, agencies and Crown Corporations'),
            ],
            'regulated-private-sector' => [
                'name' => __('Federally Regulated private sector'),
                'description' => __('Banks, federal transportation network (airlines, rail, road and marine transportation providers that cross provincial or international borders), atomic energy, postal and courier services, the broadcasting and telecommunications sectors'),
            ],
            'military-law-enforcement' => [
                'name' => __('The Canadian Forces and the Royal Canadian Mounted Police'),
            ],
            'parliamentary' => [
                'name' => __('Parliamentary entities'),
                'description' => __('House of Commons, Senate, Library of Parliament, Parliamentary Protective Service'),
            ],
        ];

        foreach ($sectors as $sector) {
            Sector::firstOrCreate([
                'name' => [
                    'en' => $sector['name'],
                    'fr' => trans($sector['name'], [], 'fr'),
                ],
                'description' => isset($sector['description']) ? [
                    'en' => $sector['description'],
                    'fr' => trans($sector['description'], [], 'fr'),
                ] : null,
            ]);
        }
    }
}
