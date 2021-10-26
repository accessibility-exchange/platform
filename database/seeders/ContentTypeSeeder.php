<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content_types = [
            'Guidelines and best practices',
            'Practical guides and how tos',
            'Templates and forms',
            'Case studies',
        ];

        foreach ($content_types as $content_type) {
            DB::table('content_types')->insert([
                'name' => json_encode(['en' => $content_type]),
            ]);
        }
    }
}
