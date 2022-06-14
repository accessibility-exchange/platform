<?php

namespace App\Models;

use App\Traits\HasMultipageEditingAndPublishing;
use Carbon\Carbon;
use Hearth\Traits\HasInvitations;
use Hearth\Traits\HasMembers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Makeable\EloquentStatus\HasStatus;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Organization extends Model
{
    use CascadesDeletes;
    use HasFactory;
    use HasInvitations;
    use HasMembers;
    use HasMultipageEditingAndPublishing;
    use HasStatus;
    use HasTranslations;
    use HasTranslatableSlug;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'type',
        'languages',
        'working_languages',
        'consulting_services',
        'locality',
        'region',
        'about',
        'service_areas',
        'area_types',
        'social_links',
        'website_link',
        'cross_disability',
        'other_disability_type',
        'refugees_and_immigrants',
        'trans_people',
        'twoslgbtqia',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'service_areas' => 'array',
        'area_types' => 'array',
        'languages' => 'array',
        'working_languages' => 'array',
        'consulting_services' => 'array',
        'social_links' => 'array',
        'published_at' => 'datetime:Y-m-d',
        'cross_disability' => 'boolean',
        'other_disability_type' => 'array',
        'refugees_and_immigrants' => 'boolean',
        'trans_people' => 'boolean',
        'twoslgbtqia' => 'boolean',
    ];

    /**
     * The relationships that should be deleted when an organization is deleted.
     *
     * @var array
     */
    protected mixed $cascadeDeletes = [
        'users',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array<string>
     */
    public array $translatable = [
        'name',
        'slug',
        'about',
        'other_disability_type',
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
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the route prefix for the model.
     *
     * @return string
     */
    public function getRoutePrefix(): string
    {
        return 'organizations';
    }

    public function getRoutePlaceholder(): string
    {
        return 'organization';
    }

    /**
     * Get the projects that belong to this organization.
     *
     * @return MorphMany
     */
    public function projects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this organization that are in progress.
     *
     * @return MorphMany
     */
    public function inProgressProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            })
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this organization that have been completed.
     *
     * @return MorphMany
     */
    public function completedProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    /**
     * Get the projects that belong to this organization that haven't started yet.
     *
     * @return MorphMany
     */
    public function upcomingProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }

    public function hasAddedDetails(): bool
    {
        return ! is_null($this->region);
    }

    public function isPublishable(): bool
    {
        return ! is_null($this->languages)
            && ! is_null($this->working_languages)
            && ! is_null($this->locality)
            && ! is_null($this->region)
            && ! is_null($this->about)
            && ! is_null($this->service_areas)
            && ! is_null($this->area_types);
    }

    public function blocks(): MorphToMany
    {
        return $this->morphToMany(User::class, 'blockable');
    }

    public function blockedBy(?User $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        return $this->blocks()->where('user_id', $user->id)->exists();
    }

    public function notificationRecipients(): MorphToMany
    {
        return $this->morphToMany(User::class, 'notificationable');
    }

    public function isNotifying(?User $user): bool
    {
        if (is_null($user)) {
            return false;
        }

        return $this->notificationRecipients()->where('user_id', $user->id)->exists();
    }

    public function organizationRoles(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationRole::class);
    }

    /**
     * Is the individual a participant?
     *
     * @return bool
     */
    public function isParticipant(): bool
    {
        $participantRole = OrganizationRole::where('name->en', 'Consultation participant')->first();

        return $this->organizationRoles->contains($participantRole);
    }

    /**
     * Is the individual an accessibility consultant?
     *
     * @return bool
     */
    public function isConsultant(): bool
    {
        $consultantRole = OrganizationRole::where('name->en', 'Accessibility consultant')->first();

        return $this->organizationRoles->contains($consultantRole);
    }

    /**
     * Is the individual a community connector?
     *
     * @return bool
     */
    public function isConnector(): bool
    {
        $connectorRole = OrganizationRole::where('name->en', 'Community connector')->first();

        return $this->organizationRoles->contains($connectorRole);
    }

    public function livedExperienceConstituencies(): MorphToMany
    {
        return $this->morphedByMany(LivedExperience::class, 'constituentable')->orderBy('name');
    }

    public function disabilityConstituencies(): MorphToMany
    {
        return $this->morphedByMany(DisabilityType::class, 'constituentable')->orderBy('name');
    }

    public function indigenousConstituencies(): MorphToMany
    {
        return $this->morphedByMany(IndigenousIdentity::class, 'constituentable')->orderBy('name');
    }

    public function genderIdentityConstituencies(): MorphToMany
    {
        return $this->morphedByMany(GenderIdentity::class, 'constituentable')->orderBy('name');
    }

    public function ageBracketConstituencies(): MorphToMany
    {
        return $this->morphedByMany(AgeBracket::class, 'constituentable')->orderBy('name');
    }

    public function ethnoracialConstituencies(): MorphToMany
    {
        return $this->morphedByMany(EthnoracialIdentity::class, 'constituentable')->orderBy('name');
    }

    public function employmentStatusConstituencies(): MorphToMany
    {
        return $this->morphedByMany(EmploymentStatus::class, 'constituentable')->orderBy('name');
    }

    public function getBaseDisabilityTypeAttribute(): string|null
    {
        $disabilityLivedExperience = LivedExperience::find(1);

        if ($this->livedExperienceConstituencies->contains($disabilityLivedExperience)) {
            return $this->cross_disability
                ? 'cross_disability'
                : 'specific_disabilities';
        }

        return null;
    }

    public function getBaseIndigenousIdentityAttribute(): int
    {
        return $this->indigenousConstituencies->count() > 0
            ? 1
            : 0;
    }

    public function getBaseGenderAndSexualIdentityAttribute(): int
    {
        return $this->genderIdentityConstituencies->count() > 0 || $this->trans_people || $this->twoslgbtqia
            ? 1
            : 0;
    }
}
