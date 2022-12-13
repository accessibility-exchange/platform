<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->create([
                'name' => 'IRIS Institute',
                'email' => 'contact@irisinstitute.ca',
                'email_verified_at' => now(),
                'context' => 'administrator',
            ]);

        $this->call([
            DatabaseSeeder::class,
            // Seed known resource collections and resources
            ResourceCollectionSeeder::class,
            ResourceSeeder::class,
        ]);
    }
}
