<?php

namespace Database\Seeders;

use App\Models\AgeBracket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgeBracketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ageBrackets = [
            [
                'name' => __('Children (under 15)'),
                'min' => null,
                'max' => 14,
            ],
            [
                'name' => __('Youth (15–30)'),
                'min' => 15,
                'max' => 30,
            ],
            [
                'name' => __('Working age adults (15–64)'),
                'min' => 15,
                'max' => 64,
            ],
            [
                'name' => __('Older people (65+)'),
                'min' => 65,
                'max' => null,
            ],
        ];

        foreach ($ageBrackets as $ageBracket) {
            AgeBracket::firstOrCreate([
                'name->en' => $ageBracket['name'],
                'name->fr' => trans($ageBracket['name'], [], 'fr'),
                'min' => $ageBracket['min'],
                'max' => $ageBracket['max'],
            ]);
        }
    }
}
