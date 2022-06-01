<?php

namespace App\Models;

use Hearth\Models\Membership;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Collection;
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
     * @var array
     */
    protected mixed $cascadeDeletes = [
        'organizations',
        'regulatedOrganizations',
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
            'individual' => __('Video for individuals.'),
            'organization' => __('Video for community organizations.'),
            'regulated-organization' => __('Video for regulated organizations.'),
            'regulated-organization-employee' => __('Video for regulated organization employees.'),
            default => '',
        };
    }

    /**
     * Get the individual page associated with the user.
     */
    public function individual(): HasOne
    {
        return $this->hasOne(Individual::class);
    }

    /**
     * Get the user's resources.
     *
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * Get the user's resource collections.
     *
     * @return HasMany
     */
    public function resourceCollections(): HasMany
    {
        return $this->hasMany(ResourceCollection::class);
    }

    /**
     * Get the user's memberships.
     *
     * @return HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the organizations that belong to this user.
     *
     * @return MorphToMany
     */
    public function organizations(): MorphToMany
    {
        return $this->morphedByMany(Organization::class, 'membershipable', 'memberships')
            ->using(Membership::class)
            ->as('membership')
            ->withPivot(['role', 'id'])
            ->withTimestamps();
    }

    /**
     * Get the regulated organizations that belong to this user.
     *
     * @return MorphToMany
     */
    public function regulatedOrganizations(): MorphToMany
    {
        return $this->morphedByMany(RegulatedOrganization::class, 'membershipable', 'memberships')
            ->using(Membership::class)
            ->as('membership')
            ->withPivot(['role', 'id'])
            ->withTimestamps();
    }

    /**
     * Get the organization that belongs to the user.
     *
     * @return mixed
     */
    public function getOrganizationAttribute(): mixed
    {
        return $this->organizations->first();
    }

    /**
     * Get the regulated organization that belongs to the user.
     *
     * @return mixed
     */
    public function getRegulatedOrganizationAttribute(): mixed
    {
        return $this->regulatedOrganizations->first();
    }

    /**
     * Get the parent projectable model.
     *
     * @return Organization|RegulatedOrganization|null
     */
    public function projectable(): Organization|RegulatedOrganization|null
    {
        if ($this->context === 'organization') {
            return $this->organization;
        }

        if ($this->context === 'regulated-organization') {
            return $this->regulatedOrganization;
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
        if ($this->projectable()->projects->isNotEmpty()) {
            return $this->projectable()->projects;
        }

        return new Collection([]);
    }

    /**
     * Determine if the user is a member of a given membershipable model.
     *
     * @param mixed $model
     * @return bool
     */
    public function isMemberOf(mixed $model): bool
    {
        return $model->hasUserWithEmail($this->email);
    }

    /**
     * Determine if the user is an administrator of a given model.
     *
     * @param mixed $model
     * @return bool
     */
    public function isAdministratorOf(mixed $model): bool
    {
        return $model->hasAdministratorWithEmail($this->email);
    }

    public function blockedOrganizations(): MorphToMany
    {
        return $this->morphedByMany(Organization::class, 'blockable')->orderBy('name');
    }

    public function blockedRegulatedOrganizations(): MorphToMany
    {
        return $this->morphedByMany(RegulatedOrganization::class, 'blockable')->orderBy('name');
    }

    public function blockedIndividuals(): MorphToMany
    {
        return $this->morphedByMany(Individual::class, 'blockable')->orderBy('name');
    }

    public function organizationsForNotification(): MorphToMany
    {
        return $this->morphedByMany(Organization::class, 'notificationable')->orderBy('name');
    }

    public function regulatedOrganizationsForNotification(): MorphToMany
    {
        return $this->morphedByMany(RegulatedOrganization::class, 'notificationable')->orderBy('name');
    }

    public function isReceivingNotificationsFor(RegulatedOrganization|Organization $notificationable): bool
    {
        return $notificationable->isNotifying($this);
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
