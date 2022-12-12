<?php

namespace Database\Factories;

use App\Models\ResourceCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceCollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResourceCollection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => ['en' => $this->faker->words(3, true)],
            'description' => ['en' => $this->faker->sentence()],
        ];
    }
}
