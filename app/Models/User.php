<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class User extends Authenticatable implements HasLocalePreference, MustVerifyEmail
{
    use CascadesDeletes;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'theme',
        'context',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The relationships that should be deleted when a user is deleted.
     *
     * @var array
     */
    protected $cascadeDeletes = [
        'organizations',
    ];

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * Get the consultant profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the user's memberships.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<Membership>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the consulting organizations that belong to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\MorphToMany<Organization>
     */
    public function organizations(): MorphToMany
    {
        return $this->morphedByMany(Organization::class, 'membership')
            ->using('\App\Models\Membership')
            ->withPivot('id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the regulated entities that belong to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\MorphToMany<Entity>
     */
    public function entities(): MorphToMany
    {
        return $this->morphedByMany(Entity::class, 'membership')
            ->using('\App\Models\Membership')
            ->withPivot('id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Determine if the user is a member of a given memberable.
     *
     * @param mixed $memberable
     * @return bool
     */
    public function isMemberOf($memberable)
    {
        return $memberable->hasUserWithEmail($this->email);
    }

    /**
     * Determine if the user is an administrator of a given memberable.
     *
     * @param mixed $memberable
     * @return bool
     */
    public function isAdministratorOf($memberable)
    {
        return $memberable->hasAdministratorWithEmail($this->email);
    }

    /**
     * Is two-factor authentication enabled for this user?
     *
     * @return bool
     */
    public function twoFactorAuthEnabled()
    {
        return ! is_null($this->two_factor_secret);
    }
}
