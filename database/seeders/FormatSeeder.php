<?php

namespace Database\Seeders;

use App\Models\Format;
use Illuminate\Database\Seeder;

class FormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formats = [
            'Text',
            'Video',
            'Audio',
            'PDF',
            'Word document',
        ];

        foreach ($formats as $format) {
            Format::firstOrCreate([
                'name' => $format,
            ]);
        }
    }
}
