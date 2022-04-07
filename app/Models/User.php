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
use Illuminate\Support\Collection;
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
     * @var array<string>
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
     * @var array<int, string>
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
     * @var array<string, string>
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
     * Get the community member page associated with the user.
     */
    public function communityMember(): HasOne
    {
        return $this->hasOne(CommunityMember::class);
    }

    /**
     * Get the user's resources.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<Resource>
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
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
     * Get the community organizations that belong to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
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
     * Get the federally regulated organizations that belong to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function regulatedOrganizations(): MorphToMany
    {
        return $this->morphedByMany(RegulatedOrganization::class, 'membership')
            ->using('\App\Models\Membership')
            ->withPivot('id')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the federally regulated organization that belongs to this user.
     *
     * @return \App\Models\RegulatedOrganization|null
     */
    public function regulatedOrganization(): mixed
    {
        return $this->regulatedOrganizations->first();
    }

    /**
     * Get the federally regulated organization that belongs to this user.
     *
     * @return \App\Models\Organization|null
     */
    public function organization(): mixed
    {
        return $this->organizations->first();
    }

    /**
     * Get the organization or federally regulated organization that belongs to this user.
     *
     * @return \App\Models\Organization|\App\Models\RegulatedOrganization|null
     */
    public function projectable(): mixed
    {
        if ($this->context === 'organization') {
            return $this->organization();
        }

        if ($this->context === 'regulated-organization') {
            return $this->regulatedOrganization();
        }

        return null;
    }

    /**
     * Get the projects associated with all organizations and regulated organizations that belong to this user.
     *
     * @return \Illuminate\Support\Collection
     */
    public function projects(): Collection
    {
        $projects = collect([]);

        if ($this->context === 'organization') {
            if ($this->organization()->projects->isNotEmpty()) {
                $projects = $projects->merge($this->organization()->projects);
            }
        }

        if ($this->context === 'regulated-organization') {
            if ($this->regulatedOrganization()->projects->isNotEmpty()) {
                $projects = $projects->merge($this->regulatedOrganization()->projects);
            }
        }

        return $projects;
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
