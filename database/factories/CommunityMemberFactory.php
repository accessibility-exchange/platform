<?php

namespace Database\Factories;

use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CommunityMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => function (array $attributes) {
                return User::find($attributes['user_id'])->name;
            },
            'locality' => $this->faker->city(),
            'region' => $this->faker->provinceAbbr(),
            'pronouns' => ['en' => $this->faker->randomElement(['He/him/his', 'She/her/hers', 'They/them/theirs'])],
            'published_at' => date('Y-m-d h:i:s', time()),
            'creator' => 'self',
            'roles' => ['participant'],
        ];
    }
}
