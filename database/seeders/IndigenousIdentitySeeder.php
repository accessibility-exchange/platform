<?php

namespace Database\Seeders;

use App\Models\IndigenousIdentity;
use Illuminate\Database\Seeder;

class IndigenousIdentitySeeder extends Seeder
{
    public function run()
    {
        $identities = [
            [
                'name' => __('First Nations'),
            ],
            [
                'name' => __('Inuit'),
            ],
            [
                'name' => __('Métis'),
            ],
        ];

        foreach ($identities as $identity) {
            IndigenousIdentity::firstOrCreate([
                'name->en' => $identity['name'],
                'name->fr' => trans($identity['name'], [], 'fr'),
                'description->en' => $identity['description'] ?? null,
                'description->fr' => isset($identity['description']) ? trans($identity['description'], [], 'fr') : null,
            ]);
        }
    }
}
