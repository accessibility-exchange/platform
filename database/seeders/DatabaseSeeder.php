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
        $projects = Project::all();

        foreach ($projects as $project) {
            $project = Project::find(1);
            $project->payment_negotiable = true;
            $project->goals = 'Here’s a brief description of what we hope to accomplish in this consultation process.';
            $project->impact = 'The outcomes of this project will impact existing and new customers who identify as having a disability, or who are support people for someone with a disability.';
            $project->out_of_scope = 'Here are a few things that are not part of the scope of this project.';
            $project->virtual_consultation = true;
            $project->timeline = 'Timeline to be determined based on participants’ availability.';
            $project->existing_clients = true;
            $project->prospective_clients = true;
            $project->flexible_deadlines = true;
            $project->flexible_breaks = true;
            $project->min = 20;
            $project->max = 20;
            $project->regions = get_region_codes(['CA']);
            $project->priority_outreach = '_Reason:_ This project is focused on removing barriers for members of Indigenous communities.';
            $project->anything_else = 'New consultants welcomed.';
            $project->save();
            $project->communities()->attach(2);
            $project->consultingMethods()->attach($consultingMethods->random(2));
            $project->impacts()->attach($impacts->random(2));
            $project->paymentMethods()->attach($paymentMethods->random(2));
            $project->accessSupports()->attach($accessSupports);
            $project->communicationTools()->attach($communicationTools->random(3));
        }

        $entity = Entity::first();
        $entity->sectors()->attach($sectors->random());

        for ($i = 0; $i < 20; $i++) {
            Consultant::factory()
            ->hasAttached($communities->random())
            ->hasAttached($impacts->random(4))
            ->hasAttached($livedExperiences->random())
            ->hasAttached($paymentMethods->random(4))
            ->hasAttached($sectors->random(4))
            ->hasAttached($accessSupports->random(2))
            ->create();
        }
    }
}
