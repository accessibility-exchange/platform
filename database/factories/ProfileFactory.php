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
        $regions = get_region_codes();

        return [
            'name' => $this->faker->company(),
            'bio' => $this->faker->paragraph(2),
            'locality' => $this->faker->city(),
            'region' => $regions[$this->faker->numberBetween(0, 12)],
            'birth_date' => $this->faker->date('Y-m-d'),
            'pronouns' => $this->faker->randomElement(['He/him/his', 'She/her/hers', 'They/them/theirs']),
            'user_id' => User::factory(),
            'status' => 'published',
        ];
    }
}
