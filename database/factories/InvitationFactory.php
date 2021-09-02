<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roles = config('hearth.organizations.roles');

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'role' => $roles[$this->faker->numberBetween(0, count($roles) - 1)],
            'inviteable_id' => Organization::factory(),
            'inviteable_type' => 'App\Models\Organization',
        ];
    }
}
