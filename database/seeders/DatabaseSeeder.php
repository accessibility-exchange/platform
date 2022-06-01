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
            AgeGroupSeeder::class,
            CommunicationToolSeeder::class,
            IndividualRoleSeeder::class,
            CommunitySeeder::class,
            ConsultingMethodSeeder::class,
            ContentTypeSeeder::class,
            FormatSeeder::class,
            ImpactSeeder::class,
            LivedExperienceSeeder::class,
            PaymentMethodSeeder::class,
            PhaseSeeder::class,
            SectorSeeder::class,
            TopicSeeder::class,
        ]);
    }
}
