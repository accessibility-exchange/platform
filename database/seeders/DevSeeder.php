<?php

namespace Database\Seeders;

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
        $this->call([
            DatabaseSeeder::class,
            CollectionSeeder::class,
        ]);
    }
}
