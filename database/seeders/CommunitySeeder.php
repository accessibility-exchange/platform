<?php

namespace Database\Seeders;

use App\Models\Community;
use Illuminate\Database\Seeder;

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
            Community::firstOrCreate([
                'name' => $community,
            ]);
        }
    }
}
