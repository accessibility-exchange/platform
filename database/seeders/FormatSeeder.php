<?php

namespace Database\Seeders;

use App\Models\Format;
use Illuminate\Database\Seeder;

class FormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formats = [
            __('Text'),
            __('Video'),
            __('Audio'),
            __('PDF'),
            __('Word document'),
        ];

        foreach ($formats as $format) {
            Format::firstOrCreate([
                'name->en' => $format,
                'name->fr' => trans($format, [], 'fr'),
            ]);
        }
    }
}
