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
            AgeBracketSeeder::class,
            AreaTypeSeeder::class,
            CommunicationToolSeeder::class,
            DisabilityTypeSeeder::class,
            EmploymentStatusSeeder::class,
            EthnoracialIdentitySeeder::class,
            GenderIdentitySeeder::class,
            IndigenousIdentitySeeder::class,
            ConstituencySeeder::class,
            ConsultingMethodSeeder::class,
            ContentTypeSeeder::class,
            ImpactSeeder::class,
            LanguageSeeder::class,
            LivedExperienceSeeder::class,
            PaymentTypeSeeder::class,
            PhaseSeeder::class,
            SectorSeeder::class,
            TopicSeeder::class,
        ]);
    }
}
