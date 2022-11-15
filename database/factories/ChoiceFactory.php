<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Choice>
 */
class ChoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $option = $this->faker->words(3, true);

        return [
            'value' => $option,
            'label' => ['en' => $option],
            'question_id' => Question::factory(),
            'is_answer' => false,
        ];
    }
}
