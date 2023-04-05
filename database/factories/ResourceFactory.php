<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => ['en' => $this->faker->words(3, true)],
            'author' => ['en' => $this->faker->company()],
            'summary' => $this->faker->sentence(),
            'url' => ['en' => $this->faker->url()],
        ];
    }
}
