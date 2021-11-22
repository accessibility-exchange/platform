<?php

namespace Database\Seeders;

use App\Models\Entity;
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

        $entity = Entity::factory()
            ->create([
                'name' => 'ABC Corporation',
            ]);
    }
}
