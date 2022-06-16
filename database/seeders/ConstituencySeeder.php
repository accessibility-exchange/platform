<?php

namespace Database\Seeders;

use App\Models\Constituency;
use Illuminate\Database\Seeder;

class ConstituencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $constituencies = [
            [
                'name' => __('Refugee or immigrant'),
                'name_plural' => __('Refugees and/or immigrants'),
                'adjective' => __('Refugee or immigrant'),
            ],
            [
                'name' => __('Trans person'),
                'name_plural' => __('Trans people'),
                'adjective' => __('Trans'),
            ],
            [
                'name' => __('2SLGBTQIA+ person'),
                'name_plural' => __('2SLGBTQIA+ people'),
                'adjective' => __('2SLGBTQIA+'),
            ],
        ];

        foreach ($constituencies as $constituency) {
            Constituency::firstOrCreate([
                'name->en' => $constituency['name'],
                'name->fr' => trans($constituency['name'], [], 'fr'),
                'name_plural->en' => $constituency['name_plural'],
                'name_plural->fr' => trans($constituency['name_plural'], [], 'fr'),
                'adjective->en' => $constituency['adjective'],
                'adjective->fr' => trans($constituency['adjective'], [], 'fr'),
                'description->en' => $constituency['description'] ?? null,
                'description->fr' => isset($constituency['description']) ? trans($constituency['description'], [], 'fr') : null,
            ]);
        }
    }
}
