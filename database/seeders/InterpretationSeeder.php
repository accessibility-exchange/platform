<?php

namespace Database\Seeders;

use App\Models\Interpretation;
use Illuminate\Database\Seeder;

class InterpretationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = file_get_contents('database/seeders/data/Interpretations.json');
        $data = json_decode($contents, true);

        foreach ($data ?? [] as $routeName => $routeData) {
            foreach ($routeData['interpretations'] as $interpretation) {
                Interpretation::firstOrCreate(
                    [
                        'name' => $interpretation['name'],
                        'namespace' => $interpretation['namespace'] ?? $routeName,
                    ],
                    array_merge($interpretation, [
                        'route' => $routeName,
                    ])
                );
            }
        }
    }
}
