<?php

namespace App\Models;

use App\Traits\HasContactPerson;
use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use App\Traits\HasSchemalessAttributes;
use Carbon\Carbon;
use Hearth\Traits\HasMembers;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Makeable\EloquentStatus\HasStatus;
use Makeable\QueryKit\QueryKit;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Organization extends Model
{
    use CascadesDeletes;
    use HasContactPerson;
    use HasFactory;
    use HasSchemalessAttributes;
    use HasMembers;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasStatus;
    use HasTranslations;
    use HasTranslatableSlug;
    use Notifiable;
    use QueryKit;

    protected $attributes = [
        'preferred_contact_method' => 'email',
        'preferred_notification_method' => 'email',
    ];

    protected $fillable = [
        'published_at',
        'name',
        'type',
        'languages',
        'region',
        'locality',
        'about',
        'service_areas',
        'working_languages',
        'consulting_services',
        'social_links',
        'website_link',
        'extra_attributes',
        'other_disability_type',
        'other_ethnoracial_identity',
        'staff_lived_experience',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_vrs',
        'preferred_contact_method',
        'preferred_notification_method',
        'notification_settings',
    ];

    protected $casts = [
        'published_at' => 'datetime:Y-m-d',
        'name' => 'array',
        'languages' => 'array',
        'about' => 'array',
        'service_areas' => 'array',
        'working_languages' => 'array',
        'consulting_services' => 'array',
        'social_links' => 'array',
        'other_disability_type' => 'array',
        'other_ethnoracial_identity' => 'array',
        'contact_person_phone' => E164PhoneNumberCast::class.':CA',
        'contact_person_vrs' => 'boolean',
        'notification_settings' => SchemalessAttributes::class,
    ];

    protected mixed $cascadeDeletes = [
        'users',
    ];

    public array $translatable = [
        'name',
        'slug',
        'about',
        'other_disability_type',
        'other_ethnoracial_identity',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRoutePrefix(): string
    {
        return 'organizations';
    }

    public function getRoutePlaceholder(): string
    {
        return 'organization';
    }

    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'invitationable');
    }

    protected function serviceRegions(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => get_regions_from_provinces_and_territories(json_decode($attributes['service_areas']) ?? []),
        );
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
            && ! is_null($this->contact_person_name);
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
        $participantRole = OrganizationRole::where('name->en', 'Consultation Participant')->first();

        return $this->organizationRoles->contains($participantRole);
    }

    /**
     * Is the individual an accessibility consultant?
     *
     * @return bool
     */
    public function isConsultant(): bool
    {
        $consultantRole = OrganizationRole::where('name->en', 'Accessibility Consultant')->first();

        return $this->organizationRoles->contains($consultantRole);
    }

    /**
     * Is the individual a Community Connector?
     *
     * @return bool
     */
    public function isConnector(): bool
    {
        $connectorRole = OrganizationRole::where('name->en', 'Community Connector')->first();

        return $this->organizationRoles->contains($connectorRole);
    }

    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    public function livedExperiences(): BelongsToMany
    {
        return $this->belongsToMany(LivedExperience::class)->withTimestamps();
    }

    public function areaTypes(): BelongsToMany
    {
        return $this->belongsToMany(AreaType::class)->withTimestamps();
    }

    public function disabilityTypes(): BelongsToMany
    {
        return $this->belongsToMany(DisabilityType::class)->withTimestamps();
    }

    public function indigenousIdentities(): BelongsToMany
    {
        return $this->belongsToMany(IndigenousIdentity::class)->withTimestamps();
    }

    public function genderIdentities(): BelongsToMany
    {
        return $this->belongsToMany(GenderIdentity::class)->withTimestamps();
    }

    public function ageBrackets(): BelongsToMany
    {
        return $this->belongsToMany(AgeBracket::class)->withTimestamps();
    }

    public function ethnoracialIdentities(): BelongsToMany
    {
        return $this->belongsToMany(EthnoracialIdentity::class)->withTimestamps();
    }

    public function constituencies(): BelongsToMany
    {
        return $this->belongsToMany(Constituency::class)->withTimestamps();
    }

    public function constituentLanguages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class)->withTimestamps();
    }

    public function getBaseDisabilityTypeAttribute(): string|false
    {
        if ($this->disabilityTypes->count() > 0) {
            return $this->disabilityTypes->contains(DisabilityType::where('name->en', 'Cross-disability')->first())
                ? 'cross_disability'
                : 'specific_disabilities';
        } elseif ($this->other_disability_type) {
            return 'specific_disabilities';
        }

        return false;
    }

    public function getHasNbGncFluidConstituentsAttribute(): bool
    {
        return $this->genderIdentities->contains(GenderIdentity::where('name_plural->en', 'Non-binary people')->firstOrFail())
            || $this->genderIdentities->contains(GenderIdentity::where('name_plural->en', 'Gender non-conforming people')->firstOrFail())
            || $this->genderIdentities->contains(GenderIdentity::where('name_plural->en', 'Gender fluid people')->firstOrFail());
    }
}
