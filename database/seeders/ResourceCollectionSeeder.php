<?php

namespace Database\Seeders;

use App\Models\ResourceCollection;
use App\Models\User;
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
                'user_id' => User::where('context', 'administrator')->first()->id,
                'title->en' => $resourceCollection['title'],
                'description->en' => $resourceCollection['description'] ?? '',
            ]);
        }
    }
}
