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
            __('Employment'),
            __('Buildings and public spaces'),
            __('Information and communication technologies'),
            __('Communication'),
            __('Buying goods, services, facilities'),
            __('Programs and services'),
            __('Transportation'),
        ];

        foreach ($impacts as $impact) {
            Impact::firstOrCreate([
                'name->en' => $impact,
                'name->fr' => trans($impact, [], 'fr'),
            ]);
        }
    }
}
