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
            'Black community',
            'Indigenous community',
            '2SLGBTQIA+ community',
            'Newcomer or refugee community',
        ];

        foreach ($communities as $community) {
            DB::table('communities')->insert([
                'name' => json_encode(['en' => $community]),
            ]);
        }
    }
}
