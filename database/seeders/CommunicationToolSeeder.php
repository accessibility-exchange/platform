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
            __('Email'),
            __('Phone calls'),
            __('Google Meet'),
            __('Microsoft Teams'),
            __('Skype'),
            __('WebEx'),
            __('Zoom'),
        ];

        foreach ($tools as $tool) {
            CommunicationTool::firstOrCreate([
                'name->en' => $tool,
                'name->fr' => trans($tool, [], 'fr'),
            ]);
        }
    }
}
