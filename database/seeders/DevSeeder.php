<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('en_CA');

        $this->call([
            DatabaseSeeder::class,
            CollectionSeeder::class,
        ]);

        $communityMember = User::factory()
            ->create([
                'name' => 'Jonny Appleseed',
                'email' => 'jonny@example.net',
                'email_verified_at' => now(),
            ]);

        $consultantPage = Consultant::factory()
            ->create([
                'user_id' => $communityMember->id,
                'name' => $communityMember->name,
                'bio' => $faker->sentences(3),
                'locality' => 'Toronto',
                'region' => 'ON',
                'pronouns' => 'He/him/his',
                'email' => $communityMember->email,
                'phone' => $faker->phoneNumber(),
            ]);

        $entityRepresentative = User::factory()
            ->create([
                'name' => 'Daniel Addison',
                'email' => 'daniel.addison@example.com',
                'email_verified_at' => now(),
                'context' => 'entity',
            ]);

        $entity = Entity::factory()
            ->hasAttached($entityRepresentative, ['role' => 'admin'])
            ->create([
                'name' => 'Example Corporation',
            ]);
    }
}
