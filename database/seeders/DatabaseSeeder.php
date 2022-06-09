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
            IndividualRoleSeeder::class,
            OrganizationRoleSeeder::class,
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
