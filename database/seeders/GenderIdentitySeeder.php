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
                'name' => __('Woman'),
                'name_plural' => __('Women'),
                'adjective' =>  __('Female'),
            ],
            [
                'name' => __('Man'),
                'name_plural' => __('Men'),
                'adjective' =>  __('Male'),
            ],
            [
                'name' => __('Non-binary person'),
                'name_plural' => __('Non-binary people'),
                'adjective' =>  __('Non-binary'),
            ],
            [
                'name' => __('Gender non-conforming person'),
                'name_plural' => __('Gender non-conforming people'),
                'adjective' =>  __('Gender non-conforming'),
            ],
            [
                'name' => __('Gender fluid person'),
                'name_plural' => __('Gender fluid people'),
                'adjective' =>  __('Gender fluid'),
            ],
        ];

        foreach ($identities as $identity) {
            GenderIdentity::firstOrCreate([
                'name->en' => $identity['name'],
                'name->fr' => trans($identity['name'], [], 'fr'),
                'name_plural->en' => $identity['name_plural'],
                'name_plural->fr' => trans($identity['name_plural'], [], 'fr'),
                'adjective->en' => $identity['adjective'],
                'adjective->fr' => trans($identity['adjective'], [], 'fr'),
                'description->en' => $identity['description'] ?? null,
                'description->fr' => isset($identity['description']) ? trans($identity['description'], [], 'fr') : null,
            ]);
        }
    }
}
