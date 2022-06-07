<?php

namespace Database\Seeders;

use App\Models\OrganizationRole;
use Illuminate\Database\Seeder;

class OrganizationRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => __('Accessibility consultant'),
                'description' => __('Federally Regulated Entities can hire my organization to design and run consultations, as well as to synthesize results and to contribute systemic analysis'),
            ],
            [
                'name' => __('Community connector'),
                'description' => __('Federally Regulated Entities can hire my organization to recruit consultation participants for them'),
            ],
            [
                'name' => __('Consultation participant'),
                'description' => __('Allow Federally Regulated Entities to reach out to my organization to participate in consultation'),
            ],
        ];

        foreach ($roles as $role) {
            OrganizationRole::firstOrCreate([
                'name->en' => $role['name'],
                'name->fr' => trans($role['name'], [], 'fr'),
                'description->en' => $role['description'],
                'description->fr' => trans($role['description'], [], 'fr'),
            ]);
        }
    }
}
