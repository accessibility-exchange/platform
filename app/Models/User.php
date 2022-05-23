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
        'signed_language',
        'theme',
        'context',
        'finished_introduction',
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
        'finished_introduction' => 'boolean',
    ];

    /**
     * The relationships that should be deleted when a user is deleted.
     *
     * @var mixed
     */
    protected mixed $cascadeDeletes = [
        'organizations',
    ];

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale(): string
    {
        return $this->locale;
    }

    /**
     * Get the introduction for the user.
     *
     * @return string
     */
    public function introduction(): string
    {
        return match ($this->context) {
            'community-member' => __('Video for community members.'),
            'organization' => __('Video for community organizations.'),
            'regulated-organization' => __('Video for regulated organizations.'),
            'regulated-organization-employee' => __('Video for regulated organization employees.'),
            default => '',
        };
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
     * @return HasMany
     *
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * Get the user's memberships.
     *
     * @return HasMany
     *
     * @return HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the community organizations that belong to this user.
     *
     * @return MorphToMany
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
     * @return MorphToMany
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
     * @return RegulatedOrganization|null
     */
    public function regulatedOrganization(): RegulatedOrganization|null
    {
        return $this->regulatedOrganizations->first();
    }

    /**
     * Get the federally regulated organization that belongs to this user.
     *
     * @return Organization|null
     */
    public function organization(): Organization|null
    {
        return $this->organizations->first();
    }

    /**
     * Get the organization or federally regulated organization that belongs to this user.
     *
     * @return Organization|RegulatedOrganization|null
     */
    public function projectable(): Organization|RegulatedOrganization|null
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
     * Get the projects associated with all organizations or regulated organizations that belong to this user.
     *
     * @return Collection
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
    public function isMemberOf(mixed $memberable): bool
    {
        return $memberable->hasUserWithEmail($this->email);
    }

    /**
     * Determine if the user is an administrator of a given memberable.
     *
     * @param mixed $memberable
     * @return bool
     */
    public function isAdministratorOf(mixed $memberable): bool
    {
        return $memberable->hasAdministratorWithEmail($this->email);
    }

    /**
     * Is two-factor authentication enabled for this user?
     *
     * @return bool
     */
    public function twoFactorAuthEnabled(): bool
    {
        return ! is_null($this->two_factor_secret);
    }
}
