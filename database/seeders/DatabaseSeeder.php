<?php

namespace Database\Seeders;

use App\Models\AccessSupport;
use App\Models\CommunicationTool;
use App\Models\Community;
use App\Models\Consultant;
use App\Models\ConsultingMethod;
use App\Models\Entity;
use App\Models\Impact;
use App\Models\LivedExperience;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\Sector;
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
            CommunitySeeder::class,
            CommunicationToolSeeder::class,
            ConsultingMethodSeeder::class,
            ImpactSeeder::class,
            LivedExperienceSeeder::class,
            PaymentMethodSeeder::class,
            SectorSeeder::class,
        ]);

        $accessSupports = AccessSupport::all();
        $communicationTools = CommunicationTool::whereIn('name->en', ['Email', 'Phone calls', 'Zoom'])->get();
        $communities = Community::all();
        $consultingMethods = ConsultingMethod::all();
        $impacts = Impact::all();
        $livedExperiences = LivedExperience::all();
        $paymentMethods = PaymentMethod::all();
        $sectors = Sector::all();

        Entity::factory(1)
            ->has(
                Project::factory(1)
                    ->hasAttached($consultingMethods->random(2))
                    ->hasAttached($impacts->random(2))
                    ->hasAttached($paymentMethods->random(2))
                    ->hasAttached($accessSupports)
                    ->hasAttached($communicationTools->random(3))
            )
            ->hasAttached($sectors->random())
            ->create();

        Consultant::factory(100)
            ->hasAttached($communities->random())
            ->hasAttached($impacts->random(4))
            ->hasAttached($livedExperiences->random())
            ->hasAttached($paymentMethods->random(4))
            ->hasAttached($sectors->random(4))
            ->create();
    }
}
