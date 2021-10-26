<?php

namespace Database\Seeders;

use App\Models\ContentType;
use Illuminate\Database\Seeder;

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
            ContentType::firstOrCreate([
                'name' => $content_type,
            ]);
        }
    }
}
