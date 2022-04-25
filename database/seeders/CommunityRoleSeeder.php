<?php

namespace Database\Seeders;

use App\Models\CommunityRole;
use Illuminate\Database\Seeder;

class CommunityRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => __('Consultation participant'),
                'description' => __('Participate in consultations'),
            ],
            [
                'name' => __('Accessibility consultant'),
                'description' => __('Help regulated organizations design and implement their consultations'),
            ],
            [
                'name' => __('Community connector'),
                'description' => __('Connect organizations with my participants from community'),
            ],
        ];

        foreach ($roles as $role) {
            CommunityRole::firstOrCreate([
                'name->en' => $role['name'],
                'name->fr' => trans($role['name'], [], 'fr'),
                'description->en' => $role['description'],
                'description->fr' => trans($role['description'], [], 'fr'),
            ]);
        }
    }
}
