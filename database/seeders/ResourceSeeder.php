<?php

namespace Database\Seeders;

use App\Models\ContentType;
use App\Models\Impact;
use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\Sector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // check if there is a JSON file that has stored data for the seeder
        if (Storage::disk('seeds')->exists('resources.json')) {
            $resources = json_decode(Storage::disk('seeds')->get('resources.json'), true);

            foreach ($resources as $resource) {
                $item = Resource::firstOrCreate([
                    'title' => json_decode($resource['title'], true),
                    'slug' => json_decode($resource['slug'], true),
                    'summary' => json_decode($resource['summary'], true),
                    'url' => json_decode($resource['url'], true),
                    'formats' => json_decode($resource['formats'], true),
                    'phases' => json_decode($resource['phases'], true),
                    'author' => json_decode($resource['author'], true),
                    'content_type_id' => $resource['content_type_id'],
                    'organization_id' => $resource['organization_id'],
                ]);
            }
        } else {
            $resources = [
                [
                    'title' => ['en' => 'The Accessible Canada Act, Accessibility Regulations and Standards'],
                    'author' => ['en' => 'ARCH Disability Law Centre'],
                    'url' => [
                        'en' => 'https://archdisabilitylaw.ca/the-accessible-canada-act-accessibility-regulations-and-standards/',
                        'asl' => 'https://www.youtube.com/watch?v=CE8OFr9jdXw',
                        'fr' => 'https://archdisabilitylaw.ca/fr/la-loi-canadienne-sur-laccessibilite-les-reglements-daccessibilite-et-les-normes-daccessibilite/',
                        'lsq' => 'https://www.youtube.com/watch?v=D5D6J8QFyX4',
                    ],
                    'phases' => ['design'],
                    'type' => ContentType::firstWhere('name->en', 'Guidelines and best practices'),
                    'sectors' => [Sector::firstWhere('name->en', 'Federal government programs and services')->id],
                    'impacts' => [Impact::firstWhere('name->en', 'Programs and services')->id, Impact::firstWhere('name->en', 'Communication, other than information and communication technologies')->id],
                    'topics' => [],
                    'resourceCollections' => [ResourceCollection::firstWhere('title->en', 'The Accessible Canada Act')->id],
                ],
                [
                    'title' => ['en' => 'An Introduction To The Accessible Canada Act'],
                    'author' => ['en' => 'ARCH Disability Law Centre'],
                    'url' => ['en' => 'https://archdisabilitylaw.ca/an-introduction-to-the-accessible-canada-act/'],
                    'phases' => ['design'],
                    'sectors' => [Sector::firstWhere('name->en', 'Federal government programs and services')->id],
                    'impacts' => [Impact::firstWhere('name->en', 'Programs and services')->id, Impact::firstWhere('name->en', 'Communication, other than information and communication technologies')->id],
                    'topics' => [],
                    'resourceCollections' => [ResourceCollection::firstWhere('title->en', 'The Accessible Canada Act')->id],
                ],
            ];

            foreach ($resources as $resource) {
                $item = Resource::firstOrCreate([
                    'title' => $resource['title'],
                    'author' => $resource['author'] ?? '',
                    'summary' => $resource['summary'] ?? '',
                    'url' => $resource['url'],
                    'phases' => $resource['phases'] ?? [],
                ]);

                // TODO how do we save and do the connected values?
                if (isset($resource['type'])) {
                    $item->contentType()->associate($resource['type']);
                    $item->save();
                }

                if (isset($resource['sectors'])) {
                    $item->sectors()->attach($resource['sectors']);
                }

                if (isset($resource['impacts'])) {
                    $item->impacts()->attach($resource['impacts']);
                }

                if (isset($resource['topics'])) {
                    $item->topics()->attach($resource['topics']);
                }

                if (isset($resource['resourceCollections'])) {
                    $item->resourceCollections()->attach($resource['resourceCollections']);
                }
            }
        }
    }
}
