<?php

namespace Database\Seeders;

use App\Models\ResourceCollection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ResourceCollectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = \Faker\Factory::create('en_CA');

        // option to use environment to restore or backup to different environment files
        if (config('seeder.environment') !== null && in_array(config('seeder.environment'), ['production', 'staging', 'local']) === true) {
            $environment = config('seeder.environment');
        } else {
            $environment = config('app.env');
        }

        // check if there is a JSON file that has stored data for the seeder
        if (in_array(config('app.env'), ['testing', 'production']) !== true) {

            // if trucate was set via seeder restore command then truncate the table prior to seeding data
            if (config('seeder.truncate')) {
                DB::statement("SET foreign_key_checks=0");
                ResourceCollection::truncate();
                DB::statement("SET foreign_key_checks=1");
            }
            // TODO need to write handling of attachments
            if (false && Storage::disk('seeds')->exists(sprintf("resource_collections.%s.json", $environment))) {

                $resourceCollections = json_decode(Storage::disk('seeds')->get(sprintf("resource_collections.%s.json", $environment)), true);

                foreach ($resourceCollections as $resourceCollection) {
                    ResourceCollection::firstOrCreate([
                        'title' => json_decode($resourceCollection['title'], true),
                        'slug' => json_decode($resourceCollection['slug'], true),
                        'description' => json_decode($resourceCollection['description'], true),
                    ]);
                }
            } else {
                print("Seeder file wasn't found, using default values\r\n");
                $resourceCollections = [
                    [
                        'title' => 'The Accessible Canada Act',
                    ],
                ];

                foreach ($resourceCollections as $resourceCollection) {
                    ResourceCollection::firstOrCreate([
                        'title->en' => $resourceCollection['title'],
                        'description->en' => $resourceCollection['description'] ?? '',
                    ]);
                }
            }
        } else {
            $environment = config('app.env');
            printf("Seeder cannot be run on environment: %s\r\n", $environment);
        }
    }
}
