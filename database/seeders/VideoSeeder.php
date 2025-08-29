<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = file_get_contents('database/seeders/data/Videos.json');
        $data = json_decode($contents, true);

        foreach ($data ?? [] as $routeName => $routeData) {
            foreach ($routeData['videos'] as $video) {
                Video::firstOrCreate(
                    [
                        'name' => $video['name'],
                        'namespace' => $video['namespace'] ?? $routeName,
                    ],
                    array_merge($video, [
                        'route' => $routeName,
                    ])
                );
            }
        }
    }
}
