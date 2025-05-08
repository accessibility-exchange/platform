<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasMembers
{
    /**
     * Get all the members of the model.
     *
     * @return MorphToMany<User, $this, MorphPivot, 'membership'>
     */
    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'membershipable', 'memberships')
            ->as('membership')
            ->withPivot(['role', 'id'])
            ->withTimestamps();
    }

    /**
     * Get all the administrators of the model.
     *
     * @return MorphToMany<User, $this, MorphPivot, 'membership'>
     */
    public function administrators(): MorphToMany
    {
        return $this->morphToMany(User::class, 'membershipable', 'memberships')
            ->as('membership')
            ->wherePivot('role', 'admin')
            ->withPivot('id')
            ->withTimestamps();
    }

    /**
     * Determine if the given email address belongs to a member of the model.
     */
    public function hasUserWithEmail(string $email): bool
    {
        return $this->users->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given email address belongs to an administrator of the model.
     */
    public function hasAdministratorWithEmail(string $email): bool
    {
        return $this->administrators->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }
}
