<?php

namespace Database\Seeders;

use App\Models\GenderIdentity;
use Illuminate\Database\Seeder;

class GenderIdentitySeeder extends Seeder
{
    public function run(): void
    {
        $identities = [
            [
                'name' =>  __('Female'),
            ],
            [
                'name' =>  __('Male'),
            ],
            [
                'name' =>  __('Non-binary'),
            ],
            [
                'name' =>  __('Gender non-conforming'),
            ],
            [
                'name' =>  __('Gender fluid'),
            ],
            [
                'name' =>  __('Not listed'),
            ],
        ];

        foreach ($identities as $identity) {
            GenderIdentity::firstOrCreate([
                'name->en' => $identity['name'],
                'name->fr' => trans($identity['name'], [], 'fr'),
                'description->en' => $identity['description'] ?? null,
                'description->fr' => isset($identity['description']) ? trans($identity['description'], [], 'fr') : null,
            ]);
        }
    }
}
