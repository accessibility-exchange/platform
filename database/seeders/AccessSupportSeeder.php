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
                'anonymizable' => true,
            ],
            [
                'name' => __('Large text'),
                'virtual' => true,
                'in_person' => true,
                'documents' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Reminders'),
                'description' => __('For events or for submitting engagement documents'),
                'virtual' => true,
                'in_person' => true,
                'documents' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('CART (Communication Access Realtime Translation)'),
                'virtual' => true,
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Sign language interpretation'),
                'virtual' => true,
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Spoken language interpretation'),
                'virtual' => true,
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Audio description for visuals'),
                'virtual' => true,
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Materials in advance'),
                'virtual' => true,
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Note-taking services'),
                'virtual' => true,
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Follow-up calls or emails'),
                'virtual' => true,
                'in_person' => true,
                'anonymizable' => false,
            ],
            [
                'name' => __('Bring my support person'),
                'in_person' => true,
                'anonymizable' => false,
            ],
            [
                'name' => __('Disconnected rooms for down-time'),
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Safe walk program'),
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Bring my service or therapy animal'),
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Intervenor'),
                'in_person' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Sign language translation'),
                'documents' => true,
                'anonymizable' => false,
            ],
            [
                'name' => __('Written language translation'),
                'documents' => true,
                'anonymizable' => false,
            ],
            [
                'name' => __('Braille version of engagement documents'),
                'documents' => true,
                'anonymizable' => false,
            ],
            [
                'name' => __('Alternative text for images'),
                'documents' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Printed version of engagement documents'),
                'documents' => true,
                'anonymizable' => false,
            ],
            [
                'name' => __('Captioning for videos'),
                'documents' => true,
                'anonymizable' => true,
            ],
            [
                'name' => __('Audio versions of engagement documents'),
                'documents' => true,
                'anonymizable' => false,
            ],
            [
                'name' => __('Someone to call and walk you through the information'),
                'documents' => true,
                'anonymizable' => false,
            ],
            [
                'name' => __('I would like to speak to someone to discuss additional access needs or concerns'),
                'in_person' => true,
                'virtual' => true,
                'documents' => true,
                'anonymizable' => false,
            ],
        ];

        foreach ($supports as $support) {
            AccessSupport::firstOrCreate([
                'name->en' => $support['name'],
                'name->fr' => trans($support['name'], [], 'fr'),
                'description->en' => $support['description'] ?? null,
                'description->fr' => isset($support['description']) ? trans($support['description'], [], 'fr') : null,
                'in_person' => $support['in_person'] ?? false,
                'virtual' => $support['virtual'] ?? false,
                'documents' => $support['documents'] ?? false,
                'anonymizable' => $support['anonymizable'] ?? false,
            ]);
        }
    }
}
