<?php

namespace App\Models;

use App\Enums\OrganizationRole;
use App\Enums\ProvinceOrTerritory;
use App\Models\Scopes\OrganizationNotSuspendedScope;
use App\Traits\HasContactPerson;
use App\Traits\HasDisplayRegion;
use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use App\Traits\HasSchemalessAttributes;
use Carbon\Carbon;
use Hearth\Traits\HasMembers;
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

class Organization extends Model
{
    use CascadesDeletes;
    use HasContactPerson;
    use HasDisplayRegion;
    use HasFactory;
    use HasSchemalessAttributes;
    use HasMembers;
    use HasMergedRelationships;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasRelationships;
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
        'oriented_at',
        'validated_at',
        'suspended_at',
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

    protected static function booted()
    {
        static::addGlobalScope(new OrganizationNotSuspendedScope);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(['en', 'fr'])
            ->generateSlugsFrom(function (Organization $model, $locale): string {
                return $model->getTranslation('name', $locale);
            })
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
            'extra_attributes.has_age_brackets' => 'required',
            'extra_attributes.has_ethnoracial_identities' => 'required',
            'extra_attributes.has_gender_and_sexual_identities' => 'required',
            'extra_attributes.has_refugee_and_immigrant_constituency' => 'required',
            'extra_attributes.has_indigenous_identities' => 'required',
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

        if (! $this->livedExperiences()->count()) {
            return false;
        }

        if (! $this->areaTypes()->count()) {
            return false;
        }

        if ($this->extra_attributes['has_age_brackets'] && ! $this->ageBrackets()->count()) {
            return false;
        }

        if ($this->extra_attributes['has_ethnoracial_identities'] && ! $this->ethnoracialIdentities()->count()) {
            return false;
        }

        if (
            $this->extra_attributes['has_gender_and_sexual_identities'] &&
            ! $this->genderIdentities()->count() &&
            ! $this->constituencies->contains(Constituency::firstWhere('name->en', 'Trans person')) &&
            ! $this->constituencies->contains(Constituency::firstWhere('name->en', '2SLGBTQIA+ person'))
        ) {
            return false;
        }

        if ($this->extra_attributes['has_indigenous_identities'] && ! $this->indigenousIdentities()->count()) {
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

    public function displayRoles(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_map(fn ($role) => OrganizationRole::labels()[$role], $this->roles),
        );
    }
}
