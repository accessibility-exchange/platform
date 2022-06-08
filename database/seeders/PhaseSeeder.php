<?php

namespace Database\Seeders;

use App\Models\Phase;
use Illuminate\Database\Seeder;

class PhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phases = [
            __('Learn'),
            __('Engage'),
            __('Deepen understanding'),
        ];

        foreach ($phases as $phase) {
            Phase::firstOrCreate([
                'name->en' => $phase,
                'name->fr' => trans($phase, [], 'fr'),
            ]);
        }
    }
}
