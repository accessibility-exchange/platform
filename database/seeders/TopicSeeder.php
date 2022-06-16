<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = [
            __('Accessible consultation'),
            __('Intersectional outreach'),
            __('Contracts'),
            __('Privacy'),
            __('Disability knowledge'),
        ];

        foreach ($topics as $topic) {
            Topic::firstOrCreate([
                'name->en' => $topic,
                'name->fr' => trans($topic, [], 'fr'),
            ]);
        }
    }
}
