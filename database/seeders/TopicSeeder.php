<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if there is a JSON file that has stored data for the seeder
        if (in_array(config('app.env'), ['testing', 'production']) !== true && Storage::disk('seeds')->exists('topics.json')) {
            $topics = json_decode(Storage::disk('seeds')->get('topics.json'), true);

            foreach ($topics as $topic) {
                Topic::firstOrCreate([
                    'name' => json_decode($topic['name'], true),
                ]);
            }
        } else {
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
}
