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
                'title' => 'Individuals',
                'description' => 'Build up your skills and refine your practice to participate in more challenging projects.',
            ],
            [
                'title' => 'Federally regulated organizations',
                'description' => 'Explore best practices, tools, and resources that help you set up for accessibility projects.',
            ],
            [
                'title' => 'Deaf and Disability organizations',
                'description' => 'Support your members on their path of becoming accessibility individuals.',
            ],
            [
                'title' => 'Disability knowledge and awareness',
                'description' => $faker->sentence(),
            ],
            [
                'title' => 'Accessible and intersectional consultation',
                'description' => $faker->sentence(),
            ],
            [
                'title' => 'Best practices and guidelines',
                'description' => $faker->sentence(),
            ],
            [
                'title' => 'Financial and legal considerations',
                'description' => $faker->sentence(),
            ],
        ];

        foreach ($resourceCollections as $resourceCollection) {
            ResourceCollection::firstOrCreate([
                'user_id' => User::where('context', 'administrator')->firstOrCreate([
                    'context' => 'administrator',
                    'name' => 'Administrator',
                    'email' => 'admin@accessibilityexchange.ca',
                    'email_verified_at' => now(),
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'locale' => config('app.locale'),
                    'theme' => 'system',
                ])->id,
                'title->en' => $resourceCollection['title'],
                'description->en' => $resourceCollection['description'],
            ]);
        }
    }
}
