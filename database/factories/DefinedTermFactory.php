<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DefinedTermFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'term' => ['en' => 'Federally regulated organization'],
            'definition' => ['en' => 'A business, organization or government agency which is regulated under the Accessible Canada Act.'],
        ];
    }
}
