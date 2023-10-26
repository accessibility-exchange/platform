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
        // fix for when it runs in environments without access to S3 bucket
        try {
            // try connecting to the seeds S3 bucket
            Storage::disk('seeds');
        } catch (\Exception $e) {
            // mock the seeds filesystem locally
            Storage::fake('seeds');
        }

        if (Storage::disk('seeds')->exists(sprintf('%s/topics.%s.json', config('filesystems.disks.seeds.path'), $environment))) {
            // if trucate was set via seeder restore command then truncate the table prior to seeding data
            if (config('seeder.truncate')) {
                DB::statement('SET foreign_key_checks=0');
                Topic::truncate();
                DB::statement('SET foreign_key_checks=1');
            }

            $topics = json_decode(Storage::disk('seeds')->get(sprintf('%s/topics.%s.json', config('filesystems.disks.seeds.path'), $environment)), true);

            foreach ($topics as $topic) {
                Topic::firstOrCreate([
                    'name' => json_decode($topic['name'], true),
                ]);
            }
        } else {
            $this->command->info("Seeder file wasn't found, using default values.");
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
