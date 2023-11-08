<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DatabaseSeeder::class,
            // Seed known resource collections and resources
            ResourceCollectionSeeder::class,
            ResourceSeeder::class,
            InterpretationSeeder::class,
        ]);
    }
}
