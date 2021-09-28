<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            DB::table('impacts')->insert([
                'name' => json_encode(['en' => $impact]),
            ]);
        }
    }
}
