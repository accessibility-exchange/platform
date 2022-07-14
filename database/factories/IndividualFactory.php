<?php

namespace Database\Factories;

use App\Models\Individual;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IndividualFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Individual::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => function (array $attributes) {
                return User::find($attributes['user_id'])->name;
            },
            'region' => $this->faker->provinceAbbr(),
            'languages' => ['en', 'fr'],
            'first_language' => function (array $attributes) {
                return User::find($attributes['user_id'])->locale;
            },
            'working_languages' => function (array $attributes) {
                return [User::find($attributes['user_id'])->locale];
            },
            'published_at' => date('Y-m-d h:i:s', time()),
        ];
    }
}
