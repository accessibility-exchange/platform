<?php

namespace App\Models;

use App\Enums\IdentityCluster;
use App\Enums\IdentityType;
use App\Enums\LocationType;
use App\Enums\ProvinceOrTerritory;
use App\Models\Scopes\ReachableIdentityScope;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

/**
 * App\Models\MatchingStrategy
 *
 * @property SchemalessAttributes::class $extra_attributes
 */
class MatchingStrategy extends Model
{
    use HasFactory;
    use HasSchemalessAttributes;

    protected $fillable = [
        'regions',
        'locations',
        'cross_disability_and_deaf',
        'extra_attributes',
    ];

    protected $casts = [
        'regions' => 'array',
        'locations' => 'array',
        'cross_disability_and_deaf' => 'boolean',
    ];

    public function identities(): BelongsToMany
    {
        return $this->belongsToMany(Identity::class)->withTimeStamps();
    }

    public function ageBrackets(): BelongsToMany
    {
        return $this->identities()->whereJsonContains('clusters', IdentityCluster::Age);
    }

    public function areaTypes(): BelongsToMany
    {
        return $this->identities()->whereJsonContains('clusters', IdentityCluster::Area);
    }

    public function ethnoracialIdentities(): BelongsToMany
    {
        return $this->identities()->whereJsonContains('clusters', IdentityCluster::Ethnoracial);
    }

    public function genderAndSexualityIdentities(): BelongsToMany
    {
        return $this->identities()->whereJsonContains('clusters', IdentityCluster::GenderAndSexuality);
    }

    public function indigenousIdentities(): BelongsToMany
    {
        return $this->identities()->whereJsonContains('clusters', IdentityCluster::Indigenous);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class)->withTimeStamps();
    }

    public function matchable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'matchable_type', 'matchable_id');
    }

    public function hasDisabilityTypes(): bool
    {
        return $this->identities()->whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->count() > 0;
    }

    public function hasIdentity(Identity $identity): bool
    {
        return $this->identities->contains($identity);
    }

    public function hasIdentities(array $identities): bool
    {
        return Arr::has(
            $this->identities->pluck('name', 'id')->toArray(),
            collect($identities)->pluck('id')->toArray()
        );
    }

    public function locationType(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! empty($this->regions)) {
                    return LocationType::Regions->value;
                }
                if (! empty($this->locations)) {
                    return LocationType::Localities->value;
                }

                return null;
            }
        );
    }

    public function locationSummary(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (count($this->regions ?? [])) {
                    if (count($this->regions) === count(ProvinceOrTerritory::cases())) {
                        return [__('All provinces and territories')];
                    } else {
                        $regions = Arr::map($this->regions, fn ($region) => ProvinceOrTerritory::labels()[$region]);

                        return Arr::sort($regions);
                    }
                } elseif (count($this->locations ?? [])) {
                    $locations = Arr::map(
                        $this->locations ?? [],
                        fn ($location) => __(':locality, :region', ['locality' => $location['locality'], 'region' => ProvinceOrTerritory::labels()[$location['region']]])
                    );

                    return Arr::sort($locations);
                }

                return [__('All provinces and territories')];
            },
        );
    }

    public function disabilityAndDeafGroupSummary(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->hasDisabilityTypes()) {
                    return $this->identities()->whereJsonContains('clusters', IdentityCluster::DisabilityAndDeaf)->pluck('name')->toArray();
                } elseif ($this->cross_disability_and_deaf) {
                    return [__('Cross disability (includes people with disabilities, Deaf people, and supporters)')];
                }

                return [__('Cross disability (includes people with disabilities, Deaf people, and supporters)')];
            },
        );
    }

    public function otherIdentitiesSummary(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->extra_attributes->get('other_identity_type', null)) {
                    IdentityType::AgeBracket->value => $this->identities()->whereJsonContains('clusters', IdentityCluster::Age)->pluck('name')->toArray(),
                    IdentityType::GenderAndSexualIdentity->value => $this->identities()->whereJsonContains('clusters', IdentityCluster::GenderAndSexuality)->pluck('name')->toArray(),
                    IdentityType::IndigenousIdentity->value => $this->identities()->whereJsonContains('clusters', IdentityCluster::Indigenous)->pluck('name')->toArray(),
                    IdentityType::EthnoracialIdentity->value => $this->identities()->whereJsonContains('clusters', IdentityCluster::Ethnoracial)->pluck('name')->toArray(),
                    IdentityType::RefugeeOrImmigrant->value => $this->identities()->whereJsonContains('clusters', IdentityCluster::Status)->pluck('name')->toArray(),
                    IdentityType::FirstLanguage->value => $this->languages->map(fn ($language) => $language->name)->toArray(),
                    IdentityType::AreaType->value => $this->identities()->whereJsonContains('clusters', IdentityCluster::Area)->pluck('name')->toArray(),
                    default => [__('Intersectional - This engagement is looking for people who have all sorts of different identities and lived experiences, such as race, gender, age, sexual orientation, and more.')],
                };
            },
        );
    }

    public function detachClusters(array $clusters)
    {
        foreach ($clusters as $cluster) {
            $this->identities()->detach(
                $this->identities()
                    ->withoutGlobalScope(ReachableIdentityScope::class)
                    ->whereJsonContains('clusters', $cluster)
                    ->pluck('identity_id')
                    ->toArray()
            );
        }
    }

    /**
     * Synchronize associations with identities from a single identity cluster.
     */
    public function syncRelatedIdentities(IdentityCluster $cluster, int|array $identities, ?string $weight = null, bool $detaching = true): void
    {
        if (! is_array($identities)) {
            $identities = [$identities];
        }

        if ($detaching) {
            $this->detachClusters([$cluster]);
        }

        foreach ($identities as $id) {
            $this->identities()->attach(
                $id,
                ['weight' => $weight === 'equal' ? 1 / count($identities) : null]
            );
        }
    }

    public function syncMutuallyExclusiveIdentities(IdentityCluster $cluster, int|array $identities, array $mutuallyExclusiveClusters, bool $detachLanguages = true, ?string $weight = null, bool $detaching = true): void
    {
        if (! is_array($identities)) {
            $identities = [$identities];
        }

        $this->detachClusters($mutuallyExclusiveClusters);

        if ($detachLanguages) {
            $this->languages()->detach();
        }

        if ($detaching) {
            $this->detachClusters([$cluster]);
        }

        foreach ($identities as $id) {
            $this->identities()->attach(
                $id,
                ['weight' => $weight === 'equal' ? 1 / count($identities) : null]
            );
        }
    }
}
