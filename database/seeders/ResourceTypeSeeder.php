<?php

namespace Database\Seeders;

use App\Models\ResourceType;
use Illuminate\Database\Seeder;

class ResourceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content_types = [
            __('Guidelines and best practices'),
            __('Practical guides and how tos'),
            __('Templates and forms'),
            __('Case studies'),
        ];

        foreach ($content_types as $content_type) {
            ResourceType::firstOrCreate([
                'name->en' => $content_type,
                'name->fr' => trans($content_type, [], 'fr'),
            ]);
        }
    }
}
