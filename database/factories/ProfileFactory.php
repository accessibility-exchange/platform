<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $regions = config('regions');

        return [
            'name' => $this->faker->company(),
            'locality' => $this->faker->city(),
            'region' => $regions[$this->faker->numberBetween(0, 12)],
            'user_id' => User::factory(),
        ];
    }
}
