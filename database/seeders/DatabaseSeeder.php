<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\Entity;
use App\Models\Impact;
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
            ImpactSeeder::class,
            PaymentMethodSeeder::class,
            SectorSeeder::class,
        ]);

        $impacts = Impact::all();
        $paymentMethods = PaymentMethod::all();
        $sectors = Sector::all();

        Entity::factory(5)
            ->has(
                Project::factory(3)
                    ->hasAttached($impacts->random(2))
                    ->hasAttached($paymentMethods->random(2))
            )
            ->hasAttached($sectors->random())
            ->create();

        Consultant::factory(100)
            ->hasAttached($impacts->random(4))
            ->hasAttached($paymentMethods->random(4))
            ->hasAttached($sectors->random(4))
            ->create();
    }
}
