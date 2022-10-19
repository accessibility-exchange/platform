<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            'en' => [
                'name' => [
                    'en' => 'English',
                    'fr' => 'Anglais',
                ],
            ],
            'fr' => [
                'name' => [
                    'en' => 'French',
                    'fr' => 'Français',
                ],
            ],
            'ase' => [
                'name' => [
                    'en' => 'American Sign Language',
                    'fr' => 'Langue des signes américaine',
                ],
            ],
            'fcs' => [
                'name' => [
                    'en' => 'Quebec Sign Language',
                    'fr' => 'Langue des signes québécoise',
                ],
            ],
        ];

        foreach ($languages as $code => $language) {
            Language::firstOrCreate(
                ['code' => $code],
                [
                    'name->en' => $language['name']['en'],
                    'name->fr' => $language['name']['fr'],
                ],
            );
        }
    }
}
