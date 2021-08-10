<?php

namespace App\Models;

use App\Models\Profile;
use App\Models\Membership;
use App\Models\Organization;
use App\Models\Entity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class User extends Authenticatable implements HasLocalePreference, MustVerifyEmail
{
    use CascadesDeletes;
    use HasFactory;
    use HasSlug;
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
        'theme'
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
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

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
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the user's memberships.
     */
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the consulting organizations that belong to this user.
     */
    public function organizations()
    {
        return $this->morphedByMany(Organization::class, 'membership')
            ->using('\App\Models\Membership')
            ->withPivot('id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the regulated entities that belong to this user.
     */
    public function entities()
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
