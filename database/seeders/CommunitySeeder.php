<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $communities = [
            'Black',
            'Indigenous',
            '2SLGBTQIA+',
            'Newcomer or refugee',
        ];

        foreach ($communities as $community) {
            DB::table('communities')->insert([
                'name' => json_encode(['en' => $community]),
            ]);
        }
    }
}
