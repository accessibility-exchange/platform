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
                'name' => 'Automatic captioning',
                'virtual' => true,
            ],
            [
                'name' => 'Plain language',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Materials in advance',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Follow-up emails',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Calendar invites',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Reminders for event',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Sign language interpretation (ASL)',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Sign language interpretation (LSQ)',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'CART (Communication Access Realtime Translation)',
                'virtual' => true,
            ],
            [
                'name' => 'Language interpretation',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Note-taking services',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Audio description',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Safe walk program',
                'in_person' => true,
            ],
            [
                'name' => 'Bringing your support person',
                'virtual' => true,
                'in_person' => true,
            ],
            [
                'name' => 'Bringing your service or therapy animal',
                'in_person' => true,
            ],
            [
                'name' => 'Gender-neutral washrooms',
                'in_person' => true,
            ],
            [
                'name' => 'Barrier-free washrooms',
                'virtual' => true,
                'in_person' => true,
            ],
        ];

        foreach ($supports as $support) {
            AccessSupport::firstOrCreate([
                'name->en' => $support['name'],
                'in_person' => $support['in_person'] ?? false,
                'virtual' => $support['virtual'] ?? false,
            ]);
        }
    }
}
