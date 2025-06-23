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
        $resourceCollections = [
            [
                'title' => 'The Accessible Canada Act',
                'featured' => 1,
                'order' => 1,
            ],
        ];

        foreach ($resourceCollections as $resourceCollection) {
            ResourceCollection::firstOrCreate([
                'title->en' => $resourceCollection['title'],
                'description->en' => $resourceCollection['description'] ?? '',
                'featured' => $resourceCollection['featured'],
                'order' => $resourceCollection['order'],
            ]);
        }
    }
}
