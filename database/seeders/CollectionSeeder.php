<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('en_CA');

        $collections = [
            [
                'title' => 'Community members',
                'description' => 'Build up your skills and refine your practice to participate in more challenging projects.',
            ],
            [
                'title' => 'Federally regulated entities',
                'description' => 'Explore best practices, tools, and resources that help you set up for accessibility projects.',
            ],
            [
                'title' => 'Deaf and Disability organizations',
                'description' => 'Support your members on their path of becoming accessibility community-members.',
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

        foreach ($collections as $collection) {
            Collection::firstOrCreate([
                'title->en' => $collection['title'],
                'description->en' => $collection['description'],
            ]);
        }
    }
}
