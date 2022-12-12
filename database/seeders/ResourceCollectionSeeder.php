<?php

namespace Database\Seeders;

use App\Models\ResourceCollection;
use Illuminate\Database\Seeder;

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
