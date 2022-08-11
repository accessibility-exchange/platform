<?php

namespace Database\Seeders;

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
                'context' => 'administrator',
            ]);

        $this->call([
            DatabaseSeeder::class,
            ResourceCollectionSeeder::class,
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
                'context' => 'regulated-organization',
            ]);

        $organizationUser = User::factory()
            ->create([
                'name' => 'Community Organization User',
                'email' => 'info+organization@accessibilityexchange.ca',
                'email_verified_at' => now(),
                'context' => 'organization',
            ]);

        $this->call([
            TestDataSeeder::class,
        ]);
    }
}
