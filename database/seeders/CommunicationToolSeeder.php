<?php

namespace Database\Seeders;

use App\Models\CommunicationTool;
use Illuminate\Database\Seeder;

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
            CommunicationTool::firstOrCreate([
                'name' => $tool,
            ]);
        }
    }
}
