<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'question' => ['en' => $this->faker->words(3, true)],
            'choices' => ['en' => [
                ['label' => $this->faker->words(3, true), 'value' => 0],
                ['label' => $this->faker->words(3, true), 'value' => 1],
                ['label' => $this->faker->words(3, true), 'value' => 2],
            ]],
            'correct_choices' => [1, 2],
        ];
    }
}
