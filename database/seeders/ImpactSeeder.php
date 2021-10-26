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
            'Employment',
            'Buildings and public spaces',
            'Information and communication technologies',
            'Communication',
            'Buying goods, services, facilities',
            'Programs and services',
            'Transportation',
        ];

        foreach ($impacts as $impact) {
            Impact::firstOrCreate([
                'name' => $impact,
            ]);
        }
    }
}
