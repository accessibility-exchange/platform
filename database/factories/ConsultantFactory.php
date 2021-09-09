<?php

namespace Database\Factories;

use App\Models\Consultant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Consultant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $regions = get_region_codes();

        return [
            'name' => $this->faker->name,
            'bio' => $this->faker->paragraph(2),
            'locality' => $this->faker->city(),
            'region' => $regions[$this->faker->numberBetween(0, 12)],
            'pronouns' => $this->faker->randomElement(['He/him/his', 'She/her/hers', 'They/them/theirs']),
            'user_id' => User::factory(),
            'published_at' => date('Y-m-d h:i:s', time()),
            'creator' => 'self',
        ];
    }
}
