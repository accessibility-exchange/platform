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
                'name' => __('Automatic captioning'),
                'virtual' => true,
            ],
            [
                'name' => __('Plain language'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Materials in advance'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Follow-up emails'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Calendar invites'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Reminders for event'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Sign language interpretation (ASL)'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Sign language interpretation (LSQ)'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('CART (Communication Access Realtime Translation)'),
                'virtual' => true,
            ],
            [
                'name' => __('Language interpretation'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Note-taking services'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Audio description'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Safe walk program'),
                'in_person' => true,
            ],
            [
                'name' => __('Bringing your support person'),
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => __('Bringing your service or therapy animal'),
                'in_person' => true,
            ],
            [
                'name' => __('Gender-neutral washrooms'),
                'in_person' => true,
            ],
            [
                'name' => __('Barrier-free washrooms'),
                'virtual' => true,
                'in_person' => true,
            ],
        ];

        foreach ($supports as $support) {
            AccessSupport::firstOrCreate([
                'name->en' => $support['name'],
                'name->fr' => trans($support['name'], [], 'fr'),
                'in_person' => $support['in_person'] ?? false,
                'virtual' => $support['virtual'] ?? false,
            ]);
        }
    }
}
