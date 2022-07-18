<?php

namespace Database\Seeders;

use App\Models\AccessSupport;
use Illuminate\Database\Seeder;

class AccessSupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supports = [
            [
                'name' => __('Plain language'),
                'virtual' => true,
                'in_person' => true,
                'documents' => true,
            ],
            [
                'name' => __('Large text'),
                'virtual' => true,
                'in_person' => true,
                'documents' => true,
            ],
            [
                'name' => __('Reminders'),
                'description' => __('For events or for submitting engagement documents'),
                'virtual' => true,
                'in_person' => true,
                'documents' => true,
            ],
            [
                'name' => __('CART (Communication Access Realtime Translation)'),
                'virtual' => true,
            ],
            [
                'name' => __('Sign language interpretation'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Spoken language interpretation'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Audio description for visuals'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Materials in advance'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Note-taking services'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Follow-up calls or emails'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Bring my support person'),
                'in_person' => true,
            ],
            [
                'name' => __('Disconnected rooms for down-time'),
                'in_person' => true,
            ],
            [
                'name' => __('Safe walk program'),
                'in_person' => true,
            ],
            [
                'name' => __('Accessible, gender neutral single-stall washroom'),
                'in_person' => true,
            ],
            [
                'name' => __('Bring my service or therapy animal'),
                'in_person' => true,
            ],
            [
                'name' => __('Intervenor'),
                'in_person' => true,
            ],
            [
                'name' => __('Sign language translation'),
                'documents' => true,
            ],
            [
                'name' => __('Written language translation'),
                'documents' => true,
            ],
            [
                'name' => __('Braille version of engagement documents'),
                'documents' => true,
            ],
            [
                'name' => __('Alternative text for images'),
                'documents' => true,
            ],
            [
                'name' => __('Printed version of engagement documents'),
                'documents' => true,
            ],
            [
                'name' => __('Captioning for videos'),
                'documents' => true,
            ],
            [
                'name' => __('Audio version of engagement documents'),
                'documents' => true,
            ],
            [
                'name' => __('Someone to call and walk you through the information'),
                'documents' => true,
            ],
            [
                'name' => __('I would like to speak to someone to discuss additional access needs or concerns'),
                'in_person' => true,
                'virtual' => true,
                'documents' => true,
            ],
        ];

        foreach ($supports as $support) {
            AccessSupport::firstOrCreate([
                'name->en' => $support['name'],
                'name->fr' => trans($support['name'], [], 'fr'),
                'description->en' => $identity['description'] ?? null,
                'description->fr' => isset($identity['description']) ? trans($identity['description'], [], 'fr') : null,
                'in_person' => $support['in_person'] ?? false,
                'virtual' => $support['virtual'] ?? false,
                'documents' => $support['documents'] ?? false,
            ]);
        }
    }
}
