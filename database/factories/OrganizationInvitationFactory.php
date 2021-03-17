<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\OrganizationInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrganizationInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $roles = config('roles');

        return [
            'email' => $this->faker->unique()->safeEmail,
            'role' => $roles[$this->faker->numberBetween(0, count($roles) - 1)],
            'organization_id' => Organization::factory()
        ];
    }
}
