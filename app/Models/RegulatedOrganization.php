<?php

namespace App\Models;

use App\Enums\ProvinceOrTerritory;
use App\Models\Scopes\OrganizationNotSuspendedScope;
use App\Traits\GeneratesMultilingualSlugs;
use App\Traits\HasDisplayRegion;
use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use Hearth\Traits\HasInvitations;
use Hearth\Traits\HasMembers;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Makeable\EloquentStatus\HasStatus;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\RegulatedOrganization
 *
 * @property SchemalessAttributes::class $extra_attributes
 */
class RegulatedOrganization extends Model implements HasLocalePreference
{
    use CascadesDeletes;
    use GeneratesMultilingualSlugs;
    use HasDisplayRegion;
    use HasFactory;
    use HasInvitations;
    use HasMembers;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasStatus;
    use HasTranslatableSlug;
    use HasTranslations;
    use Notifiable;

    protected $attributes = [
        'preferred_contact_method' => 'email',
        'preferred_notification_method' => 'email',
    ];

    protected $fillable = [
        'published_at',
        'oriented_at',
        'validated_at',
        'suspended_at',
        'dismissed_invite_prompt_at',
        'name',
        'type',
        'languages',
        'region',
        'locality',
        'about',
        'service_areas',
        'accessibility_and_inclusion_links',
        'social_links',
        'website_link',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_vrs',
        'preferred_contact_method',
        'preferred_contact_language',
        'preferred_notification_method',
        'notification_settings',
    ];

    protected $casts = [
        'published_at' => 'datetime:Y-m-d',
        'oriented_at' => 'datetime',
        'validated_at' => 'datetime',
        'suspended_at' => 'datetime',
        'name' => 'array',
        'languages' => 'array',
        'about' => 'array',
        'service_areas' => 'array',
        'accessibility_and_inclusion_links' => 'array',
        'social_links' => 'array',
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
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrganizationNotSuspendedScope);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (RegulatedOrganization $model, $locale): string {
                return $this->generateSlugs($model, $locale);
            })
            ->saveSlugsTo('slug');
    }

    public function preferredLocale(): string
    {
        return to_written_language(
            $this->preferred_contact_language
            ?? User::whereBlind('email', 'email_index', $this->contact_person_email)->first()->locale
            ?? locale()
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRoutePrefix(): string
    {
        return 'regulated-organizations';
    }

    public function getRoutePlaceholder(): string
    {
        return 'regulatedOrganization';
    }

    public function routeNotificationForMail(Notification $notification): array
    {
        return [$this->contact_person_email => $this->contact_person_name];
    }

    public function routeNotificationForVonage(Notification $notification): string
    {
        return $this->contact_person_phone;
    }

    public function singularName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => __('regulated organization'),
        );
    }

    public function invitations(): MorphMany
    {
        return $this->morphMany(Invitation::class, 'invitationable');
    }

    protected function displayServiceAreas(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Arr::map($this->service_areas, fn ($region) => ProvinceOrTerritory::labels()[$region]),
        );
    }

    /**
     * Get the individual's social links.
     */
    public function getSocialLinksAttribute(): array
    {
        if (isset($this->attributes['social_links']) && ! is_null($this->attributes['social_links'])) {
            return array_filter(json_decode($this->attributes['social_links'], true));
        }

        return [];
    }

    /**
     * Get the individual's accessibility and inclusion links.
     */
    public function getAccessibilityAndInclusionLinksAttribute(): array
    {
        if (isset($this->attributes['accessibility_and_inclusion_links']) && ! is_null($this->attributes['accessibility_and_inclusion_links'])) {
            return array_filter(json_decode($this->attributes['accessibility_and_inclusion_links'], true));
        }

        return [];
    }

    /**
     * The sectors that belong to the federally regulated organization.
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    /**
     * Get the projects that belong to this federally regulated organization.
     */
    public function projects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->orderBy('start_date');
    }

    public function draftProjects(): MorphMany
    {
        return $this->projects()->status('draft');
    }

    public function publishedProjects(): MorphMany
    {
        return $this->projects()->status('published');
    }

    /**
     * Get the projects that belong to this regulated organization that are in progress.
     */
    public function inProgressProjects(): MorphMany
    {
        return $this->publishedProjects()->statuses('inProgress');
    }

    /**
     * Get the projects that belong to this regulated organization that have been completed.
     */
    public function completedProjects(): MorphMany
    {
        return $this->publishedProjects()->statuses('completed');
    }

    /**
     * Get the projects that belong to this regulated organization that haven't started yet.
     */
    public function upcomingProjects(): MorphMany
    {
        return $this->publishedProjects()->statuses('upcoming');
    }

    /**
     * Has the user added any details to the regulated organization?
     */
    public function hasAddedDetails(): bool
    {
        return ! is_null($this->region);
    }

    public function isPreviewable(): bool
    {
        $rules = [
            'about.en' => 'required_without:about.fr',
            'about.fr' => 'required_without:about.en',
            'accessibility_and_inclusion_links.*.title' => 'required_with:accessibility_and_inclusion_links.*.url',
            'accessibility_and_inclusion_links.*.url' => 'required_with:accessibility_and_inclusion_links.*.title',
            'contact_person_email' => 'required_without:contact_person_phone|required_if:preferred_contact_method,email',
            'contact_person_phone' => 'required_if:contact_person_vrs,true|required_without:contact_person_email|required_if:preferred_contact_method,phone',
            'contact_person_name' => 'required',
            'languages' => 'required',
            'locality' => 'required',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'preferred_contact_method' => 'required',
            'region' => 'required',
            'service_areas' => 'required',
            'type' => 'required',
        ];

        try {
            Validator::validate($this->toArray(), $rules);
        } catch (ValidationException $exception) {
            return false;
        }

        if (! $this->sectors()->count()) {
            return false;
        }

        return true;
    }

    public function isPublishable(): bool
    {
        if (! $this->isPreviewable()) {
            return false;
        }

        if (! $this->checkStatus('approved')) {
            return false;
        }

        return true;
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
}
