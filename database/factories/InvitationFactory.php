<?php

namespace Database\Factories;

use App\Models\Organization;
use Hearth\Models\Invitation;
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
    public function definition(): array
    {
        $roles = config('hearth.organizations.roles');

        return [
            'email' => $this->faker->unique()->safeEmail(),
            'role' => $roles[$this->faker->numberBetween(0, count($roles) - 1)],
            'invitationable_id' => Organization::factory(),
            'invitationable_type' => 'App\Models\Organization',
        ];
    }
}
