<?php

namespace Database\Seeders;

use App\Models\Impact;
use App\Models\IndividualRole;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Sector;
use App\Models\User;
use Faker\Factory as Faker;
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
        $faker = Faker::create('en_CA');

        $this->call([
            DatabaseSeeder::class,
            // ResourceCollectionSeeder::class,
        ]);

        // Retrieve impacts.
        $communicationImpact = Impact::where('name->en', 'Communication')->first();
        $programsAndServicesImpact = Impact::where('name->en', 'Programs and services')->first();
        $transportationImpact = Impact::where('name->en', 'Transportation')->first();

        // Retrieve payment types.
        $cashPaymentMethod = PaymentMethod::where('name->en', 'Cash')->first();
        $giftCardPaymentMethod = PaymentMethod::where('name->en', 'Gift card')->first();

        // Retrieve sector.
        $transportationSector = Sector::where('name->en', 'Transportation')->first();

        $user = User::factory()
            ->create([
                'name' => 'Jonny Appleseed',
                'email' => 'jonny@example.net',
                'email_verified_at' => now(),
            ]);

        $individual = $user->individual;
        $individual->individualRoles()->sync(IndividualRole::pluck('id')->toArray());

        // Attach impacts.
        $individual->impacts()->attach([
            $communicationImpact->id,
            $programsAndServicesImpact->id,
            $transportationImpact->id,
        ]);

        // Attach payment methods.
        $individual->paymentMethods()->attach([
            $cashPaymentMethod->id,
            $giftCardPaymentMethod->id,
        ]);

        // Attach sector.
        $individual->sectors()->attach($transportationSector->id);

        $regulatedOrganizationRepresentative = User::factory()
            ->create([
                'name' => 'Daniel Addison',
                'email' => 'daniel@example.com',
                'email_verified_at' => now(),
                'context' => 'regulated-organization',
            ]);

        $regulatedOrganization = RegulatedOrganization::factory()
            ->hasAttached($regulatedOrganizationRepresentative, ['role' => 'admin'])
            ->create([
                'name' => 'Example Corporation',
                'languages' => ['en', 'fr', 'ase', 'fcs'],
                'locality' => 'Toronto',
                'region' => 'ON',
            ]);

        $regulatedOrganization->sectors()->attach($transportationSector->id);

        $completedProject = Project::factory()
            ->create([
                'name' => '2020 Accessibility Plan',
                'projectable_id' => $regulatedOrganization->id,
                'start_date' => '2020-01-01',
                'end_date' => '2020-12-31',
            ]);

        $completedProject->impacts()->attach($communicationImpact->id);

        $recruitingProject = Project::factory()
            ->create([
                'name' => '2022 Accessibility Plan',
                'projectable_id' => $regulatedOrganization->id,
                'start_date' => '2022-01-01',
                'end_date' => '2022-12-31',
            ]);

        $recruitingProject->impacts()->attach($programsAndServicesImpact->id);

        $consultingProject = Project::factory()
            ->create([
                'name' => '2021 Accessibility Plan',
                'projectable_id' => $regulatedOrganization->id,
                'start_date' => '2021-01-01',
                'end_date' => '2021-12-31',
            ]);

        $consultingProject->impacts()->attach($transportationImpact->id);
    }
}
