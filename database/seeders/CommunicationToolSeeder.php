<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunicationToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tools = [
            'Email',
            'Phone calls',
            'Google Meet',
            'Microsoft Teams',
            'Skype',
            'WebEx',
            'Zoom',
        ];

        foreach ($tools as $tool) {
            DB::table('communication_tools')->insert([
                'name' => json_encode(['en' => $tool]),
            ]);
        }
    }
}
