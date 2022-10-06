<?php

namespace App\Models;

use App\Enums\IndividualRole;
use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use App\Traits\HasSchemalessAttributes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Makeable\EloquentStatus\HasStatus;
use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\CipherSweet as CipherSweetEngine;
use ParagonIE\CipherSweet\EncryptedField;
use ParagonIE\CipherSweet\EncryptedRow;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\Relations\MergedRelation;
use TheIconic\NameParser\Parser as NameParser;

class Individual extends Model implements CipherSweetEncrypted, HasMedia
{
    use UsesCipherSweet;
    use HasFactory;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasSchemalessAttributes;
    use HasMergedRelationships;
    use HasSlug;
    use HasStatus;
    use HasTranslations;
    use InteractsWithMedia;
    use Notifiable;
    use HasRelationships;

    protected $fillable = [
        'published_at',
        'user_id',
        'name',
        'slug',
        'picture_alt',
        'languages',
        'roles',
        'pronouns',
        'bio',
        'region',
        'locality',
        'working_languages',
        'consulting_services',
        'social_links',
        'website_link',
        'extra_attributes',
        'other_disability_type_connection',
        'other_ethnoracial_identity_connection',
        'connection_lived_experience',
        'lived_experience',
        'skills_and_strengths',
        'relevant_experiences',
        'meeting_types',
        'birth_date',
        'first_language',
        'other_payment_type',
        'other_access_need',
        'signed_language_for_interpretation',
        'spoken_language_for_interpretation',
        'signed_language_for_translation',
        'written_language_for_translation',
        'street_address',
        'unit_apartment_suite',
        'postal_code',
    ];

    protected $casts = [
        'published_at' => 'datetime:Y-m-d',
        'picture_alt' => 'array',
        'languages' => 'array',
        'roles' => 'array',
        'pronouns' => 'array',
        'bio' => 'array',
        'working_languages' => 'array',
        'consulting_services' => 'array',
        'social_links' => 'array',
        'other_disability_type_connection' => 'array',
        'other_ethnoracial_identity_connection' => 'array',
        'lived_experience' => 'array',
        'skills_and_strengths' => 'array',
        'relevant_experiences' => 'array',
        'meeting_types' => 'array',
        'birth_date' => 'datetime:Y-m-d',
        'other_access_need' => 'array',
    ];

    public array $translatable = [
        'picture_alt',
        'pronouns',
        'bio',
        'other_disability_type_connection',
        'other_ethnoracial_identity_connection',
        'lived_experience',
        'skills_and_strengths',
        'other_access_need',
    ];

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow
            ->addField('name')
            ->addField('locality')
            ->addBlindIndex('locality', new BlindIndex('locality_index'))
            ->addField('region')
            ->addBlindIndex('region', new BlindIndex('region_index'));
    }

    /**
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('picture')->singleFile();
    }

    /**
     * Register media conversions for the model.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
                ->width(200)
                ->height(200);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function (Individual $individual): string {
                return (new EncryptedField(
                    app(CipherSweetEngine::class),
                    'individuals',
                    'name'
                ))->decryptValue($individual->name);
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRoutePlaceholder(): string
    {
        return 'individual';
    }

    public function getRoutePrefix(): string
    {
        return 'individuals';
    }

    public function steps(): array
    {
        return [
            1 => [
                'edit' => 'about-you',
                'show' => 'individuals.show',
            ],
            2 => [
                'edit' => $this->isConnector() ? 'groups-you-can-connect-to' : 'experiences',
                'show' => $this->isConnector() ? 'individuals.show' : 'individuals.show-experiences',
            ],
            3 => [
                'edit' => $this->isConnector() ? 'experiences' : 'interests',
                'show' => $this->isConnector() ? 'individuals.show-experiences' : 'individuals.show-interests',
            ],
            4 => [
                'edit' => $this->isConnector() ? 'interests' : 'communication-and-consultation-preferences',
                'show' => $this->isConnector() ? 'individuals.show-interests' : 'individuals.show-communication-and-consultation-preferences',
            ],
            5 => [
                'edit' => $this->isConnector() ? 'communication-and-consultation-preferences' : null,
                'show' => $this->isConnector() ? 'individuals.show-communication-and-consultation-preferences' : null,
            ],
        ];
    }

    public function getStepForKey(string $key): int
    {
        $collection = collect($this->steps());

        return array_key_first($collection->where('edit', $key)->toArray());
    }

    /**
     * Get the individual's first name.
     *
     * @return string
     */
    public function getFirstNameAttribute(): string
    {
        return (new NameParser())->parse($this->attributes['name'])->getFirstname();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function impactsOfInterest(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    public function sectorsOfInterest(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    public function paymentTypes(): BelongsToMany
    {
        return $this->belongsToMany(PaymentType::class);
    }

    public function consultingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ConsultingMethod::class);
    }

    public function constituencies(): BelongsToMany
    {
        return $this->belongsToMany(Constituency::class);
    }

    public function livedExperiences(): BelongsToMany
    {
        return $this->belongsToMany(LivedExperience::class);
    }

    public function accessSupports(): BelongsToMany
    {
        return $this->belongsToMany(AccessSupport::class);
    }

    public function projectsOfInterest(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects_of_interest');
    }

    public function engagements(): BelongsToMany
    {
        return $this->belongsToMany(Engagement::class);
    }

    public function participatingProjects(): HasManyDeep
    {
        return $this->hasManyDeepFromReverse(
            (new Project())->participants()
        )->with('engagements');
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

    public function consultingProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'individual_consultant_id');
    }

    public function consultingEngagements(): HasMany
    {
        return $this->hasMany(Engagement::class, 'individual_consultant_id');
    }

    public function consultingEngagementProjects(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->consultingEngagements(),
            (new Engagement())->project()
        );
    }

    public function connectingEngagements(): HasMany
    {
        return $this->hasMany(Engagement::class, 'individual_connector_id');
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
        return $this->mergedRelationWithModel(Project::class, 'all_individual_contracted_projects');
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

    /**
     * Is the individual publishable?
     *
     * @return bool
     */
    public function isPublishable(): bool
    {
        $publishRules = [
            'bio.*' => 'required',
            'connection_lived_experience' => [
                Rule::requiredIf(fn () => $this->isConnector()),
            ],
            'consulting_services' => [
                'nullable',
                Rule::requiredIf(fn () => $this->isConsultant()),
                Rule::excludeIf(fn () => ! $this->isConsultant()),
            ],
            'extra_attributes.has_age_brackets' => [
                Rule::requiredIf(fn () => $this->isConnector()),
            ],
            'extra_attributes.has_ethnoracial_identities' => [
                Rule::requiredIf(fn () => $this->isConnector()),
            ],
            'extra_attributes.has_gender_and_sexual_identities' => [
                Rule::requiredIf(fn () => $this->isConnector()),
            ],
            'extra_attributes.has_indigenous_identities' => [
                Rule::requiredIf(fn () => $this->isConnector()),
            ],
            'meeting_types' => 'required',
            'name' => 'required',
            'region' => 'required',
            'roles' => 'required',
        ];

        if ($this->isConnector() || $this->isConsultant()) {
            try {
                Validator::validate($this->toArray(), $publishRules);
            } catch (ValidationException $exception) {
                return false;
            }

            if ($this->isConnector()) {
                if (! $this->livedExperienceConnections()->count()) {
                    return false;
                }

                if (! $this->areaTypeConnections()->count()) {
                    return false;
                }

                if ($this->extra_attributes['has_indigenous_identities'] && ! $this->indigenousIdentityConnections()->count()) {
                    return false;
                }

                if ($this->extra_attributes['has_age_brackets'] && ! $this->ageBracketConnections()->count()) {
                    return false;
                }
            }

            return true;
        }

        return false;
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

    public function livedExperienceConnections(): MorphToMany
    {
        return $this->morphedByMany(LivedExperience::class, 'connectable');
    }

    public function areaTypeConnections(): MorphToMany
    {
        return $this->morphedByMany(AreaType::class, 'connectable');
    }

    public function disabilityTypeConnections(): MorphToMany
    {
        return $this->morphedByMany(DisabilityType::class, 'connectable');
    }

    public function indigenousIdentityConnections(): MorphToMany
    {
        return $this->morphedByMany(IndigenousIdentity::class, 'connectable');
    }

    public function genderIdentityConnections(): MorphToMany
    {
        return $this->morphedByMany(GenderIdentity::class, 'connectable');
    }

    public function ageBracketConnections(): MorphToMany
    {
        return $this->morphedByMany(AgeBracket::class, 'connectable');
    }

    public function ethnoracialIdentityConnections(): MorphToMany
    {
        return $this->morphedByMany(EthnoracialIdentity::class, 'connectable');
    }

    public function constituencyConnections(): MorphToMany
    {
        return $this->morphedByMany(Constituency::class, 'connectable');
    }

    public function languageConnections(): MorphToMany
    {
        return $this->morphedByMany(Language::class, 'connectable');
    }

    public function getBaseDisabilityTypeAttribute(): string|false
    {
        if ($this->disabilityTypeConnections->count() > 0) {
            return $this->disabilityTypeConnections->contains(DisabilityType::where('name->en', 'Cross-disability')->first())
                ? 'cross_disability'
                : 'specific_disabilities';
        } elseif (! empty($this->other_disability_type_connection)) {
            return 'specific_disabilities';
        }

        return false;
    }

    public function getHasNbGncFluidConstituentsAttribute(): bool
    {
        return $this->genderIdentityConnections->contains(GenderIdentity::where('name_plural->en', 'Non-binary people')->firstOrFail())
            || $this->genderIdentityConnections->contains(GenderIdentity::where('name_plural->en', 'Gender non-conforming people')->firstOrFail())
            || $this->genderIdentityConnections->contains(GenderIdentity::where('name_plural->en', 'Gender fluid people')->firstOrFail());
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

    public function displayRoles(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_map(fn ($role) => IndividualRole::labels()[$role], $this->roles),
        );
    }
}
