<?php

namespace Database\Seeders;

use App\Models\Interpretation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class InterpretationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if there is a JSON file that has stored data for the seeder
        if (Storage::disk('seeds')->exists('interpretations.json')) {
            $interpretations = json_decode(Storage::disk('seeds')->get('interpretations.json'), true);

            foreach ($interpretations as $interpretation) {
                Interpretation::firstOrCreate([
                    'name' => $interpretation['name'],
                    'namespace' => $interpretation['namespace'],
                    'route' => $interpretation['route'],
                    'route_has_params' => $interpretation['route_has_params'],
                    'video' => json_decode($interpretation['video'], true),
                ]);
            }
        }
    }
}
