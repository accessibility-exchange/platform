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
                'name' => __('Built environment'),
            ],
            'communication' => [
                'name' => __('Communications'),
            ],
            'information' => [
                'name' => __('Information technology'),
            ],
            'procurement' => [
                'name' => __('Procurement'),
            ],
            'policy-programs' => [
                'name' => __('Policy and programs'),
            ],
            'service-delivery' => [
                'name' => __('Service delivery'),
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
