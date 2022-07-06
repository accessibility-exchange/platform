<?php

namespace App\Models;

use App\Traits\HasMultipageEditingAndPublishing;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    use HasMultipageEditingAndPublishing;
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
        'other_lived_experience_connections',
        'other_constituency_connections',
        'vrs',
        'web_links',
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
    ];

    /**
     * The attributes that which should be cast to other types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'social_links' => 'array',
        'web_links' => 'array',
        'relevant_experiences' => 'array',
        'support_people' => 'array',
        'languages' => 'array',
        'working_languages' => 'array',
        'rural_or_remote' => 'boolean',
        'vrs' => 'boolean',
        'support_person_vrs' => 'boolean',
        'meeting_types' => 'array',
        'other_lived_experience_connections' => 'array',
        'other_constituency_connections' => 'array',
        'bio' => 'array',
        'pronouns' => 'array',
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
        'other_lived_experience_connections',
        'other_constituency_connections',
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

    /**
     * Get the individual's languages.
     *
     * @return Attribute
     */
    public function allLanguages(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => array_merge(
                [$attributes['first_language']],
                $attributes['working_languages'] ?? [],
            ),
        );
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
     * Get the individual's links.
     *
     * @return array
     */
    public function getWebLinksAttribute(): array
    {
        if (! is_null($this->attributes['web_links'])) {
            return array_filter(json_decode($this->attributes['web_links'], true));
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
     * @param  string  $value
     * @return string|null
     */
    public function getMeetingType(string $value): string|null
    {
        return match ($value) {
            'in_person' => __('In person'),
            'phone' => __('Virtual – phone'),
            'web_conference' => __('Virtual – web conference'),
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
        return $this->isConnector() || $this->isConsultant();
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

    /**
     * Get all the lived experiences that the individual can connect with.
     *
     * @return MorphToMany
     */
    public function livedExperienceConnections(): MorphToMany
    {
        return $this->morphedByMany(LivedExperience::class, 'connectable');
    }

    /**
     * Get all the constituencies that the individual can connect with.
     *
     * @return MorphToMany
     */
    public function constituencyConnections(): MorphToMany
    {
        return $this->morphedByMany(Constituency::class, 'connectable');
    }

    /**
     * Get all the age groups that the individual can connect with.
     *
     * @return MorphToMany
     */
    public function ageBracketConnections(): MorphToMany
    {
        return $this->morphedByMany(AgeBracket::class, 'connectable');
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
