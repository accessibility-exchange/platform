<?php

namespace App\Models;

use App\Enums\EngagementFormat;
use App\Enums\IdentityCluster;
use App\Enums\IndividualRole;
use App\Models\Scopes\IndividualUserNotSuspendedScope;
use App\Models\Scopes\ReachableIdentityScope;
use App\Traits\HasDisplayRegion;
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
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\Relations\MergedRelation;
use TheIconic\NameParser\Parser as NameParser;

class Individual extends Model implements CipherSweetEncrypted
{
    use UsesCipherSweet;
    use HasDisplayRegion;
    use HasFactory;
    use HasMultimodalTranslations;
    use HasMultipageEditingAndPublishing;
    use HasSchemalessAttributes;
    use HasMergedRelationships;
    use HasSlug;
    use HasStatus;
    use HasTranslations;
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
        'other_disability_connection',
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
        'consulting_methods',
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
        'other_disability_connection' => 'array',
        'other_ethnoracial_identity_connection' => 'array',
        'lived_experience' => 'array',
        'skills_and_strengths' => 'array',
        'relevant_experiences' => 'array',
        'meeting_types' => 'array',
        'birth_date' => 'datetime:Y-m-d',
        'other_access_need' => 'array',
        'consulting_methods' => 'array',
    ];

    public array $translatable = [
        'picture_alt',
        'pronouns',
        'bio',
        'other_disability_connection',
        'other_ethnoracial_identity_connection',
        'lived_experience',
        'skills_and_strengths',
        'other_access_need',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new IndividualUserNotSuspendedScope);
    }

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow
            ->addField('name')
            ->addBlindIndex('name', new BlindIndex('name_index'))
            ->addField('locality')
            ->addBlindIndex('locality', new BlindIndex('locality_index'))
            ->addField('region')
            ->addBlindIndex('region', new BlindIndex('region_index'));
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

    public function getSingularName(): string
    {
        return __('individual');
    }

    public function steps(): array
    {
        return [
            1 => [
                'edit' => 'about-you',
                'label' => __('About you'),
                'show' => 'individuals.show',
            ],
            2 => [
                'edit' => $this->isConnector() ? 'groups-you-can-connect-to' : 'experiences',
                'label' => $this->isConnector() ? __('Groups you can connect to') : __('Experiences'),
                'show' => $this->isConnector() ? 'individuals.show' : 'individuals.show-experiences',
            ],
            3 => [
                'edit' => $this->isConnector() ? 'experiences' : 'interests',
                'label' => $this->isConnector() ? __('Experiences') : __('Interests'),
                'show' => $this->isConnector() ? 'individuals.show-experiences' : 'individuals.show-interests',
            ],
            4 => [
                'edit' => $this->isConnector() ? 'interests' : 'communication-and-consultation-preferences',
                'label' => $this->isConnector() ? __('Interests') : __('Communication and consultation preferences'),
                'show' => $this->isConnector() ? 'individuals.show-interests' : 'individuals.show-communication-and-consultation-preferences',
            ],
            5 => [
                'edit' => $this->isConnector() ? 'communication-and-consultation-preferences' : null,
                'label' => __('Communication and consultation preferences'),
                'show' => $this->isConnector() ? 'individuals.show-communication-and-consultation-preferences' : null,
            ],
        ];
    }

    public function getStepForKey(string $key): int
    {
        $collection = collect($this->steps());

        return array_key_first($collection->where('edit', $key)->toArray());
    }

    public function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => (new NameParser())->parse($this->name)->getFirstname(),
        );
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

    public function accessSupports(): BelongsToMany
    {
        return $this->belongsToMany(AccessSupport::class);
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

    /** TODO: add project and engagement-level consultants.
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
     **/
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

    public function isInProgress(): bool
    {
        if ($this->identityConnections->count() > 0) {
            return true;
        }

        $attributes = $this->only([
            'pronouns',
            'bio',
            'region',
            'locality',
            'working_languages',
            'consulting_services',
            'social_links',
            'website_link',
            'other_disability_connection',
            'other_ethnoracial_identity_connection',
            'connection_lived_experience',
            'lived_experience',
            'skills_and_strengths',
            'relevant_experiences',
            'meeting_types',
        ]);

        return count(array_filter($attributes, fn ($attr) => ! blank($attr))) || count($this->extra_attributes->all());
    }

    public function isPreviewable(): bool
    {
        $rules = [
            'bio.*' => 'required',
            'connection_lived_experience' => [
                Rule::requiredIf(fn () => $this->isConnector()),
            ],
            'consulting_services' => [
                'nullable',
                Rule::requiredIf(fn () => $this->isConsultant()),
                Rule::excludeIf(fn () => ! $this->isConsultant()),
            ],
            'meeting_types' => 'required',
            'name' => 'required',
            'region' => 'required',
            'roles' => 'required',
        ];

        if ($this->isConnector() || $this->isConsultant()) {
            try {
                Validator::validate($this->toArray(), $rules);
            } catch (ValidationException $exception) {
                return false;
            }

            if ($this->isConnector()) {
                if (! $this->areaTypeConnections()->count()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function isPublishable(): bool
    {
        if ($this->isConnector() || $this->isConsultant()) {
            if (! $this->isPreviewable()) {
                return false;
            }

            if (! $this->user->checkStatus('approved')) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function isReady(): bool
    {
        if ($this->user->checkStatus('pending')) {
            return false;
        }

        if ($this->isParticipant() && $this->paymentTypes()->count() === 0 && blank($this->other_payment_type)) {
            return false;
        }

        if (($this->isConnector() || $this->isConsultant()) && $this->checkStatus('draft')) {
            return false;
        }

        return true;
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

    /**
     * Identities of this individual. Not yet used.
     */
    public function identities(): BelongsToMany
    {
        return $this->belongsToMany(Identity::class)->withTimestamps();
    }

    public function identityConnections(): BelongsToMany
    {
        return $this->belongsToMany(Identity::class, 'individual_identity_connections')->withTimestamps();
    }

    public function ageBracketConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Age);
    }

    public function areaTypeConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Area);
    }

    public function disabilityAndDeafConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf);
    }

    public function ethnoracialIdentityConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Ethnoracial);
    }

    public function genderIdentityConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Gender);
    }

    public function genderAndSexualityConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::GenderAndSexuality);
    }

    public function genderDiverseConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::GenderDiverse);
    }

    public function indigenousConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Indigenous);
    }

    public function languageConnections(): BelongsToMany
    {
        return $this->belongsToMany(Language::class)->withTimestamps();
    }

    public function livedExperienceConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::LivedExperience);
    }

    public function statusConnections(): BelongsToMany
    {
        return $this->identityConnections()->withoutGlobalScope(ReachableIdentityScope::class)->whereJsonContains('clusters', IdentityCluster::Status);
    }

    public function baseDisabilityType(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->extra_attributes->get('cross_disability_and_deaf_connections')) {
                    1 => 'cross_disability_and_deaf',
                    0 => 'specific_disabilities',
                    default => null
                };
            }
        );
    }

    public function hasConnections(string $connectionType): ?bool
    {
        if ($this->identityConnections->count() > 0) {
            return $this->$connectionType->count() > 0;
        }

        return null;
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

    public function displayConsultingMethods(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_map(fn ($method) => EngagementFormat::labels()[$method], $this->consulting_methods),
        );
    }

    public function preferredContactPerson(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->preferred_contact_person,
        );
    }

    public function preferredContactMethod(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->preferred_contact_method,
        );
    }

    public function contactEmail(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->user->preferred_contact_person) {
                'support-person' => $this->user->support_person_email,
                default => $this->user->email
            },
        );
    }

    public function contactPhone(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->user->preferred_contact_person) {
                'support-person' => $this->user->support_person_phone?->formatForCountry('CA'),
                default => $this->user->phone?->formatForCountry('CA')
            },
        );
    }

    public function contactVrs(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->requires_vrs,
        );
    }
}
