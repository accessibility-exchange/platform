<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomKey(Arr::except(get_available_languages(true), Language::pluck('code')->all())),
            'name' => function (array $attributes) {
                $code = $attributes['code'];

                return [
                    'en' => get_language_exonym($code, 'en'),
                    'asl' => get_language_exonym($code, 'en'),
                    'fr' => get_language_exonym($code, 'fr'),
                    'lsq' => get_language_exonym($code, 'fr'),
                ];
            },
        ];
    }
}
