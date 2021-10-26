<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            DB::table('sectors')->insert([
                'name' => json_encode(['en' => $format]),
            ]);
        }
    }
}
