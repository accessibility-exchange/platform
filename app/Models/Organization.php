<?php

namespace App\Models;

use App\Enums\IdentityCluster;
use App\Enums\OrganizationRole;
use App\Enums\ProvinceOrTerritory;
use App\Models\Scopes\OrganizationNotSuspendedScope;
use App\Models\Scopes\ReachableIdentityScope;
use App\Traits\GeneratesMultilingualSlugs;
use App\Traits\HasDisplayRegion;
use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use App\Traits\HasSchemalessAttributes;
use Carbon\Carbon;
use Hearth\Traits\HasMembers;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Makeable\EloquentStatus\HasStatus;
use Makeable\QueryKit\QueryKit;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\Relations\MergedRelation;

/**
 * App\Models\Organization
 *
 * @property SchemalessAttributes::class $extra_attributes
 */
class Organization extends Model implements HasLocalePreference
{
    use CascadesDeletes;
    use GeneratesMultilingualSlugs;
    use HasDisplayRegion;
    use HasFactory;
    use HasMembers;
    use HasMergedRelationships;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasRelationships;
    use HasSchemalessAttributes;
    use HasStatus;
    use HasTranslatableSlug;
    use HasTranslations;
    use Notifiable;
    use QueryKit;

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
        'roles',
        'region',
        'locality',
        'about',
        'service_areas',
        'working_languages',
        'consulting_services',
        'social_links',
        'website_link',
        'extra_attributes',
        'other_disability_constituency',
        'other_ethnoracial_identity_constituency',
        'staff_lived_experience',
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
        'roles' => 'array',
        'about' => 'array',
        'service_areas' => 'array',
        'working_languages' => 'array',
        'consulting_services' => 'array',
        'social_links' => 'array',
        'other_disability_constituency' => 'array',
        'other_ethnoracial_identity_constituency' => 'array',
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
        'other_disability_constituency',
        'other_ethnoracial_identity_constituency',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrganizationNotSuspendedScope);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (Organization $model, $locale): string {
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
        return 'organizations';
    }

    public function getRoutePlaceholder(): string
    {
        return 'organization';
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
            get: fn ($value) => __('organization.singular_name'),
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

    public function projects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->orderBy('start_date');
    }

    public function draftProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereNull('published_at')
            ->orderBy('start_date');
    }

    public function publishedProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereNotNull('published_at')
            ->orderBy('start_date');
    }

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

    public function completedProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    public function upcomingProjects(): MorphMany
    {
        return $this->morphMany(Project::class, 'projectable')
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }

    public function participatingEngagements(): HasMany
    {
        return $this->hasMany(Engagement::class);
    }

    public function participatingProjects(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->participatingEngagements(),
            (new Engagement())->project()
        );
    }

    public function inProgressParticipatingProjects(): HasManyDeep
    {
        return $this->participatingProjects()
            ->whereDate('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            });
    }

    public function completedParticipatingProjects(): HasManyDeep
    {
        return $this->participatingProjects()
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    public function upcomingParticipatingProjects(): HasManyDeep
    {
        return $this->participatingProjects()
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }

    /** TODO: add project and engagement-level consultants.
    public function consultingProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'organizational_consultant_id');
    }

    public function consultingEngagements(): HasMany
    {
        return $this->hasMany(Engagement::class, 'organizational_consultant_id');
    }

    public function consultingEngagementProjects(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->consultingEngagements(),
            (new Engagement())->project()
        );
    }
     **/
    public function connectingEngagements(): HasMany
    {
        return $this->hasMany(Engagement::class, 'organizational_connector_id');
    }

    public function connectingEngagementProjects(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->connectingEngagements(),
            (new Engagement())->project()
        );
    }

    public function contractedProjects(): MergedRelation
    {
        return $this->mergedRelationWithModel(Project::class, 'all_organization_contracted_projects');
    }

    public function inProgressContractedProjects(): MergedRelation
    {
        return $this->contractedProjects()
            ->whereDate('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            });
    }

    public function completedContractedProjects(): MergedRelation
    {
        return $this->contractedProjects()
            ->whereDate('end_date', '<', Carbon::now())
            ->orderBy('start_date');
    }

    public function upcomingContractedProjects(): MergedRelation
    {
        return $this->contractedProjects()
            ->whereDate('start_date', '>', Carbon::now())
            ->orderBy('start_date');
    }

    public function hasAddedDetails(): bool
    {
        return ! is_null($this->region);
    }

    public function isInProgress(): bool
    {
        if ($this->constituentIdentities->count() > 0) {
            return true;
        }

        $attributes = $this->only([
            'region',
            'locality',
            'about',
            'service_areas',
            'consulting_services',
            'social_links',
            'website_link',
            'other_disability_constituency',
            'other_ethnoracial_identity_constituency',
            'staff_lived_experience',
        ]);

        return count(array_filter($attributes, fn ($attr) => ! blank($attr))) || count($this->extra_attributes->all());
    }

    public function isPreviewable(): bool
    {
        $rules = [
            'about.en' => 'required_without:about.fr',
            'about.fr' => 'required_without:about.en',
            'consulting_services' => [
                Rule::requiredIf(fn () => $this->isConsultant()),
                Rule::excludeIf(fn () => ! $this->isConsultant()),
            ],
            'contact_person_name' => 'required',
            'contact_person_email' => 'required_without:contact_person_phone|required_if:preferred_contact_method,email',
            'contact_person_phone' => 'required_if:contact_person_vrs,true|required_without:contact_person_email|required_if:preferred_contact_method,phone',
            'languages' => 'required',
            'locality' => 'required',
            'name.en' => 'required_without:name.fr',
            'name.fr' => 'required_without:name.en',
            'preferred_contact_method' => 'required',
            'region' => 'required',
            'roles' => 'required',
            'service_areas' => 'required',
            'staff_lived_experience' => 'required',
            'type' => 'required',
            'working_languages' => 'required',
        ];

        try {
            Validator::validate($this->toArray(), $rules);
        } catch (ValidationException $exception) {
            return false;
        }

        if (! $this->areaTypeConstituencies()->count()) {
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

    public function isParticipant(): bool
    {
        return in_array('participant', $this->roles ?? []);
    }

    public function isConsultant(): bool
    {
        return in_array('consultant', $this->roles ?? []);
    }

    public function isConnector(): bool
    {
        return in_array('connector', $this->roles ?? []);
    }

    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    public function constituentIdentities(): BelongsToMany
    {
        return $this->belongsToMany(Identity::class)->withTimestamps();
    }

    public function ageBracketConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->whereJsonContains('clusters', IdentityCluster::Age);
    }

    public function areaTypeConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->whereJsonContains('clusters', IdentityCluster::Area);
    }

    public function disabilityAndDeafConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf);
    }

    public function ethnoracialIdentityConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Ethnoracial);
    }

    public function genderIdentityConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Gender);
    }

    public function genderAndSexualityConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::GenderAndSexuality);
    }

    public function genderDiverseConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::GenderDiverse);
    }

    public function indigenousConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->whereJsonContains('clusters', IdentityCluster::Indigenous);
    }

    public function languageConstituencies(): BelongsToMany
    {
        return $this->belongsToMany(Language::class)->withTimestamps();
    }

    public function livedExperienceConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::LivedExperience);
    }

    public function statusConstituencies(): BelongsToMany
    {
        return $this->constituentIdentities()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Status);
    }

    public function courses(): hasMany
    {
        return $this->hasMany(Course::class);
    }

    public function getContactMethodsAttribute(): array
    {
        $methods = [];

        if (! empty($this->contact_person_email)) {
            $methods[] = 'email';
        }
        if (! empty($this->contact_person_phone)) {
            $methods[] = 'phone';
        }

        return $methods;
    }

    public function baseDisabilityType(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->extra_attributes->get('cross_disability_and_deaf_constituencies')) {
                    1 => 'cross_disability_and_deaf',
                    0 => 'specific_disabilities',
                    default => ''
                };
            }
        );
    }

    public function hasConstituencies(string $constituencyType): ?bool
    {
        if ($this->constituentIdentities->count() > 0) {
            return $this->$constituencyType->count() > 0;
        }

        return null;
    }

    public function displayRoles(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_map(fn ($role) => OrganizationRole::labels()[$role], $this->roles),
        );
    }
}
