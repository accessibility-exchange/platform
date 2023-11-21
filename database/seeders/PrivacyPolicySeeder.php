<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PrivacyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::firstOrCreate(
            [
                'title->en' => 'Privacy Policy',
            ],
            [
                'title->fr' => 'Politique de confidentialité',
            ]
        );
    }
}
