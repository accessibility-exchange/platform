<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        // option to use environment to restore or backup to different environment files
        if (config('seeder.environment') !== null && in_array(config('seeder.environment'), config('backup.filament_seeders.environments')) === true) {
            $environment = config('seeder.environment');
        } else {
            $environment = config('app.env');
        }

        if (Storage::disk('seeds')->exists(sprintf('topics.%s.json', $environment))) {
            // if trucate was set via seeder restore command then truncate the table prior to seeding data
            if (config('seeder.truncate')) {
                DB::statement('SET foreign_key_checks=0');
                Topic::truncate();
                DB::statement('SET foreign_key_checks=1');
            }

            $topics = json_decode(Storage::disk('seeds')->get(sprintf('topics.%s.json', $environment)), true);

            foreach ($topics as $topic) {
                Topic::firstOrCreate([
                    'name' => json_decode($topic['name'], true),
                ]);
            }
        } else {
            echo "Seeder file wasn't found, using default values\r\n";
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
