<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AccessSupportSeeder::class,
            CommunicationToolSeeder::class,
            IdentitySeeder::class,
            ContentTypeSeeder::class,
            ImpactSeeder::class,
            LanguageSeeder::class,
            PaymentTypeSeeder::class,
            SectorSeeder::class,
            TopicSeeder::class,
        ]);
    }
}
