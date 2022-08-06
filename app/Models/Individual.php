<?php

namespace App\Models;

use App\Traits\HasMultimodalTranslations;
use App\Traits\HasMultipageEditingAndPublishing;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
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
use TheIconic\NameParser\Parser as NameParser;

class Individual extends Model implements CipherSweetEncrypted, HasMedia
{
    use UsesCipherSweet;
    use HasFactory;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasSchemalessAttributes;
    use HasSlug;
    use HasStatus;
    use HasTranslations;
    use InteractsWithMedia;
    use Notifiable;

    protected $fillable = [
        'published_at',
        'user_id',
        'name',
        'slug',
        'picture_alt',
        'languages',
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
                'show' => $this->isConnector() ? 'individuals.show-constituencies' : 'individuals.show-experiences',
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

    /**
     * The constituencies that belong to the individual.
     */
    public function constituencies(): BelongsToMany
    {
        return $this->belongsToMany(Constituency::class);
    }

    /**
     * The lived experiences that belong to the individual.
     */
    public function livedExperiences(): BelongsToMany
    {
        return $this->belongsToMany(LivedExperience::class);
    }

    /**
     * The access supports that belong to the individual.
     */
    public function accessSupports(): BelongsToMany
    {
        return $this->belongsToMany(AccessSupport::class);
    }

    /**
     * The projects that the individual is interested in.
     */
    public function projectsOfInterest(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects_of_interest');
    }

    /**
     * The engagements that the individual belongs to.
     */
    public function engagements(): BelongsToMany
    {
        return $this->belongsToMany(Engagement::class);
    }

    /**
     * Get the roles belonging to the individual.
     *
     * @return BelongsToMany
     */
    public function individualRoles(): BelongsToMany
    {
        return $this->belongsToMany(IndividualRole::class);
    }

    /**
     * Has the user added any details to the individual?
     *
     * @return bool
     */
    public function hasAddedDetails(): bool
    {
        return ! empty($this->region);
    }

    /**
     * Is the individual publishable?
     *
     * @return bool
     */
    public function isPublishable(): bool
    {
        if ($this->isConnector() || $this->isConsultant()) {
            return true;
        }

        return false;
    }

    /**
     * Is the individual a participant?
     *
     * @return bool
     */
    public function isParticipant(): bool
    {
        $participantRole = IndividualRole::where('name->en', 'Consultation Participant')->first();

        return $this->individualRoles->contains($participantRole);
    }

    /**
     * Is the individual an accessibility consultant?
     *
     * @return bool
     */
    public function isConsultant(): bool
    {
        $consultantRole = IndividualRole::where('name->en', 'Accessibility Consultant')->first();

        return $this->individualRoles->contains($consultantRole);
    }

    /**
     * Is the individual a Community Connector?
     *
     * @return bool
     */
    public function isConnector(): bool
    {
        $connectorRole = IndividualRole::where('name->en', 'Community Connector')->first();

        return $this->individualRoles->contains($connectorRole);
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
}
