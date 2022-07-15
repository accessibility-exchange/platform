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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'locality',
        'region',
        'bio',
        'social_links',
        'pronouns',
        'picture_alt',
        'phone',
        'email',
        'preferred_contact_method',
        'preferred_contact_person',
        'first_language',
        'working_languages',
        'vrs',
        'status',
        'user_id',
        'age_group',
        'rural_or_remote',
        'lived_experience',
        'skills_and_strengths',
        'relevant_experiences',
        'languages',
        'support_person_name',
        'support_person_phone',
        'support_person_email',
        'support_person_vrs',
        'meeting_types',
        'extra_attributes',
        'other_disability_type_connection',
        'other_ethnoracial_identity_connection',
        'connection_lived_experience',
        'consulting_services',
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'social_links' => 'array',
        'relevant_experiences' => 'array',
        'languages' => 'array',
        'working_languages' => 'array',
        'rural_or_remote' => 'boolean',
        'vrs' => 'boolean',
        'support_person_vrs' => 'boolean',
        'meeting_types' => 'array',
        'bio' => 'array',
        'pronouns' => 'array',
        'other_disability_type_connection' => 'array',
        'other_ethnoracial_identity_connection' => 'array',
        'consulting_services' => 'array',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = [
        'picture_alt',
        'bio',
        'pronouns',
        'lived_experience',
        'skills_and_strengths',
        'other_disability_type_connection',
        'other_ethnoracial_identity_connection',
    ];

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow
            ->addField('name')
            ->addField('locality')
            ->addBlindIndex('locality', new BlindIndex('locality_index'))
            ->addField('region')
            ->addBlindIndex('region', new BlindIndex('region_index'))
            ->addField('phone')
            ->addField('email')
            ->addField('support_person_name')
            ->addField('support_person_phone')
            ->addField('support_person_email');
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

    /**
     * Get the individual's social links.
     *
     * @return array
     */
    public function getSocialLinksAttribute(): array
    {
        if (! is_null($this->attributes['social_links'])) {
            return array_filter(json_decode($this->attributes['social_links'], true));
        }

        return [];
    }

    /**
     * Get the individual's relevant experiences.
     *
     * @return array
     */
    public function getRelevantExperiencesAttribute(): array
    {
        if (! is_null($this->attributes['relevant_experiences'])) {
            $experiences = json_decode($this->attributes['relevant_experiences'], true);

            $experiences = array_map(function ($experience) {
                return array_filter($experience);
            }, $experiences);

            return array_filter($experiences);
        }

        return [];
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
                'edit' => $this->isConnector() ? 'interests' : 'communication-and-meeting-preferences',
                'show' => $this->isConnector() ? 'individuals.show-interests' : 'individuals.show-communication-and-meeting-preferences',
            ],
            5 => [
                'edit' => $this->isConnector() ? 'communication-and-meeting-preferences' : null,
                'show' => $this->isConnector() ? 'individuals.show-communication-and-meeting-preferences' : null,
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

    /**
     * Get the individual's primary contact person.
     *
     * @return string
     */
    public function getContactPersonAttribute(): string
    {
        return $this->preferred_contact_person === 'me' ? $this->first_name : $this->support_person_name;
    }

    /**
     * Get the individual's primary contact point.
     *
     * @return string|null
     */
    public function getPrimaryContactPointAttribute(): string|null
    {
        $contactPoint = match ($this->preferred_contact_method) {
            'email' => $this->preferred_contact_person === 'me' ?
                $this->email :
                $this->support_person_email,
            'phone' => $this->preferred_contact_person === 'me' ?
                $this->phone :
                $this->support_person_phone,
            default => null,
        };

        if ($this->preferred_contact_method === 'phone' && $this->requires_vrs) {
            $contactPoint .= ".  \n".__(':contact_person requires VRS for phone calls', ['contact_person' => $this->contact_person]);
        }

        return $contactPoint;
    }

    /**
     * Determine if the individual's contact person requires VRS for phone calls.
     *
     * @return null|bool
     */
    public function getRequiresVrsAttribute(): null|bool
    {
        return $this->preferred_contact_person === 'me' ?
            $this->vrs :
            $this->support_person_vrs;
    }

    /**
     * Get a string which expresses the individual's primary contact method.
     *
     * @return string|null
     */
    public function getPrimaryContactMethodAttribute(): string|null
    {
        return match ($this->preferred_contact_method) {
            'email' => __('Send an email to :contact_qualifier:contact_person at :email.', [
                'contact_qualifier' => $this->preferred_contact_person == 'me' ? '' : __(':name’s support person, ', ['name' => $this->first_name]),
                'contact_person' => $this->preferred_contact_person == 'me' ? $this->contact_person : $this->contact_person.',',
                'email' => '['.$this->primary_contact_point.'](mailto:'.$this->primary_contact_point.')',
            ]),
            'phone' => __('Call :contact_qualifier:contact_person at :phone_number.', [
                'contact_qualifier' => $this->preferred_contact_person == 'me' ? '' : __(':name’s support person, ', ['name' => $this->first_name]),
                'contact_person' => $this->preferred_contact_person == 'me' ? $this->contact_person : $this->contact_person.',',
                'phone_number' => $this->primary_contact_point,
            ]),
            default => null
        };
    }

    /**
     * Get the individual's alternate contact point.
     *
     * @return string|null
     */
    public function getAlternateContactPointAttribute(): string|null
    {
        $contactPoint = match ($this->preferred_contact_method) {
            'email' => $this->preferred_contact_person === 'me' ?
                $this->phone :
                $this->support_person_phone,
            'phone' => $this->preferred_contact_person === 'me' ?
                $this->email :
                $this->support_person_email,
            default => null,
        };

        if ($this->preferred_contact_method === 'email' && $this->requires_vrs) {
            $contactPoint .= "  \n".__(':contact_person requires VRS for phone calls.', ['contact_person' => $this->contact_person]);
        }

        return $contactPoint;
    }

    /**
     * Get the individual's alternate contact method.
     *
     * @return string|null
     */
    public function getAlternateContactMethodAttribute(): string|null
    {
        return match ($this->preferred_contact_method) {
            'email' => $this->alternate_contact_point,
            'phone' => '['.$this->alternate_contact_point.'](mailto:'.$this->alternate_contact_point.')',
            default => null
        };
    }

    /**
     * Get the individual's phone number.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getPhoneAttribute(string|null $value): string|null
    {
        return ! is_null($value) ? str_replace(['-', '(', ')', '.', ' '], '', $value) : $value;
    }

    /**
     * Get the user that has this individual.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The impacts that belong to the individual.
     */
    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class);
    }

    /**
     * The sectors that belong to the individual.
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    /**
     * The payment methods that belong to the individual.
     */
    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class);
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
        $participantRole = IndividualRole::where('name->en', 'Consultation participant')->first();

        return $this->individualRoles->contains($participantRole);
    }

    /**
     * Is the individual an accessibility consultant?
     *
     * @return bool
     */
    public function isConsultant(): bool
    {
        $consultantRole = IndividualRole::where('name->en', 'Accessibility consultant')->first();

        return $this->individualRoles->contains($consultantRole);
    }

    /**
     * Is the individual a community connector?
     *
     * @return bool
     */
    public function isConnector(): bool
    {
        $connectorRole = IndividualRole::where('name->en', 'Community connector')->first();

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
