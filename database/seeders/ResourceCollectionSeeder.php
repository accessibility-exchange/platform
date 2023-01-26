<?php

namespace Database\Seeders;

use App\Models\ResourceCollection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ResourceCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('en_CA');

        // check if there is a JSON file that has stored data for the seeder
        if (in_array(config('app.env'), ['testing', 'production']) !== true && Storage::disk('seeds')->exists('resource_collections.json')) {
            $resourceCollections = json_decode(Storage::disk('seeds')->get('resource_collections.json'), true);

            foreach ($resourceCollections as $resourceCollection) {
                ResourceCollection::firstOrCreate([
                    'title' => json_decode($resourceCollection['title'], true),
                    'slug' => json_decode($resourceCollection['slug'], true),
                    'description' => json_decode($resourceCollection['description'], true),
                ]);
            }
        } else {
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
    }
}
