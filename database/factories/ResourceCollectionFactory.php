<?php

namespace Database\Factories;

use App\Models\ResourceCollection;
use App\Models\User;
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
            'user_id' => User::factory(),
            'title' => 'Test Resource Collection',
            'description' => $this->faker->sentence(),
        ];
    }
}
