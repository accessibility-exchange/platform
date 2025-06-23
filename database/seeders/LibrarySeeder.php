<?php

namespace Database\Seeders;

use App\Models\Library;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        $libraries = [
            [
                'title' => 'Accessible Planning and Consultations',
                'featured' => 1,
                'order' => 1,
            ],
            [
                'title' => 'Inclusive Employment',
                'featured' => 1,
                'order' => 2,
            ],
        ];

        foreach ($libraries as $library) {
            Library::firstOrCreate([
                'title->en' => $library['title'],
                'description->en' => $library['description'] ?? '',
                'featured' => $library['featured'],
                'order' => $library['order'],
            ]);
        }
    }
}
