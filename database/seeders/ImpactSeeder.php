<?php

namespace Database\Seeders;

use App\Models\Impact;
use Illuminate\Database\Seeder;

class ImpactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $impacts = [
            'employment' => [
                'name' => __('Employment'),
            ],
            'built-environment' => [
                'name' => __('The built environment'),
                'description' => __('Buildings and public spaces'),
            ],
            'information' => [
                'name' => __('Information and communication technologies'),
            ],
            'communication' => [
                'name' => __('Communication, other than information and communication technologies'),
            ],
            'procurement' => [
                'name' => __('The procurement of goods, services and facilities'),
            ],
            'design-deliver-services' => [
                'name' => __('The design and delivery of programs and services'),
            ],
            'transportation' => [
                'name' => __('Transportation'),
                'description' => __('Airlines, as well as rail, road and marine transportation providers that cross provincial or international borders'),
            ],
        ];

        foreach ($impacts as $impact) {
            Impact::firstOrCreate([
                'name' => [
                    'en' => $impact['name'],
                    'fr' => trans($impact['name'], [], 'fr'),
                ],
                'description' => isset($impact['description']) ? [
                    'en' => $impact['description'],
                    'fr' => trans($impact['description'], [], 'fr'),
                ] : null,
            ]);
        }
    }
}
