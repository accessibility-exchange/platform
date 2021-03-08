<?php

namespace Database\Factories;

use App\Models\ConsultantProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultantProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConsultantProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $regions = [
            'ab',
            'bc',
            'mb',
            'nb',
            'nl',
            'ns',
            'nt',
            'nu',
            'on',
            'pe',
            'qc',
            'sk',
            'yt'
        ];

        return [
            'name' => $this->faker->company(),
            'locality' => $this->faker->city(),
            'region' => $regions[$this->faker->numberBetween(0, 12)],
            'user_id' => User::factory(),
        ];
    }
}
