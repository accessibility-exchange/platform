<?php

namespace Database\Seeders;

use App\Enums\UserContext;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->create([
                'name' => 'Administrator',
                'email' => 'info+admin@accessibilityexchange.ca',
                'email_verified_at' => now(),
                'context' => UserContext::Administrator->value,
            ]);

        $this->call([
            DatabaseSeeder::class,
        ]);

        $user = User::factory()
            ->create([
                'name' => 'Individual User',
                'email' => 'info+individual@accessibilityexchange.ca',
                'email_verified_at' => now(),
            ]);

        $user->individual->roles = ['participant'];
        $user->individual->save();

        $regulatedOrganizationUser = User::factory()
            ->create([
                'name' => 'Regulated Organization User',
                'email' => 'info+regulated-organization@accessibilityexchange.ca',
                'email_verified_at' => now(),
                'context' => UserContext::RegulatedOrganization->value,
            ]);

        $organizationUser = User::factory()
            ->create([
                'name' => 'Community Organization User',
                'email' => 'info+organization@accessibilityexchange.ca',
                'email_verified_at' => now(),
                'context' => UserContext::Organization->value,
            ]);

        $trainingUser = User::factory()
            ->create([
                'name' => 'Training User',
                'email' => 'info+training@accessibilityexchange.ca',
                'email_verified_at' => now(),
                'context' => UserContext::TrainingParticipant->value,
            ]);

        $this->call([
            TestDataSeeder::class,
        ]);
    }
}
