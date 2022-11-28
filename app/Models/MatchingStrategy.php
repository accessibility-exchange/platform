<?php

namespace App\Models;

use App\Enums\IdentityCluster;
use App\Enums\ProvinceOrTerritory;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class MatchingStrategy extends Model
{
    use HasFactory;
    use HasSchemalessAttributes;

    protected $fillable = [
        'regions',
        'locations',
        'extra_attributes',
    ];

    protected $casts = [
        'regions' => 'array',
        'locations' => 'array',
    ];

    /** @deprecated */
    public function criteria(): HasMany
    {
        return $this->hasMany(Criterion::class);
    }

    public function identities(): BelongsToMany
    {
        return $this->belongsToMany(Identity::class)->withTimeStamps();
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
        return $this->identities()->where('cluster', IdentityCluster::DisabilityAndDeaf)->count() > 0;
    }

    public function hasDisabilityType(Identity $disabilityType): bool
    {
        return $this->identities->contains($disabilityType);
    }

    public function hasIdentity(Identity $identity): bool
    {
        return $this->identities->contains($identity);
    }

    public function hasIdentities(array $identities): bool
    {
        $identities = collect($identities);

        return $this->identities->intersect($identities)->count() === $identities->count();
    }

    public function locationType(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (! empty($this->regions)) {
                    return 'regions';
                }
                if (! empty($this->locations)) {
                    return 'localities';
                }

                return null;
            }
        );
    }

    public function locationSummary(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (count($this->regions ?? [])) {
                    if (count($this->regions) === 13) {
                        return __('All provinces and territories');
                    } else {
                        $regions = Arr::map($this->regions, fn ($region) => ProvinceOrTerritory::labels()[$region]);

                        return implode("  \n", Arr::sort($regions));
                    }
                } elseif (count($this->locations ?? [])) {
                    $locations = Arr::map($this->locations ?? [], fn ($location) => $location['locality'].', '.ProvinceOrTerritory::labels()[$location['region']]);

                    return implode("  \n", Arr::sort($locations));
                }

                return __('All provinces and territories');
            },
        );
    }

    public function disabilityAndDeafGroupSummary(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($this->hasDisabilityType(Identity::firstWhere('name->en', 'Cross-disability and Deaf'))) {
                    return __('Cross disability (includes people with disabilities, Deaf people, and supporters)');
                }

                if ($this->hasDisabilityTypes()) {
                    return implode("  \n", $this->identities()->where('cluster', IdentityCluster::DisabilityAndDeaf)->get()->map(fn ($identity) => $identity->name)->toArray());
                }

                return __('Cross disability (includes people with disabilities, Deaf people, and supporters)');
            },
        );
    }

    public function otherIdentitiesSummary(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return match ($this->extra_attributes->get('other_identity_type', null)) {
                    'age-bracket' => implode("  \n", $this->identities()->where('cluster', IdentityCluster::Age)->get()->map(fn ($identity) => $identity->name)->toArray()),
                    'gender-and-sexual-identity' => implode("  \n", $this->identities()->where('cluster', IdentityCluster::Gender)->orWhereNull('cluster')->get()->map(fn ($identity) => $identity->name)->toArray()),
                    'indigenous-identity' => implode("  \n", $this->identities()->where('cluster', IdentityCluster::Indigenous)->get()->map(fn ($identity) => $identity->name)->toArray()),
                    'ethnoracial-identity' => implode("  \n", $this->identities()->where('cluster', IdentityCluster::Ethnoracial)->get()->map(fn ($identity) => $identity->name)->toArray()),
                    'refugee-or-immigrant' => __('Refugees and/or immigrants'),
                    'first-language' => implode("  \n", $this->languages->map(fn ($language) => $language->name)->toArray()),
                    'area-type' => implode("  \n", $this->identities()->where('cluster', IdentityCluster::Area)->get()->map(fn ($identity) => $identity->name)->toArray()),
                    default => __('Intersectional')
                };
            },
        );
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
            $this->identities()->detach(
                $this->identities()
                    ->where('cluster', $cluster)
                    ->pluck('identity_id')
                    ->toArray()
            );
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

        $this->identities()->detach(
            $this->identities()
                ->whereIn('cluster', $mutuallyExclusiveClusters)
                ->orWhereNull('cluster')
                ->pluck('identity_id')
                ->toArray()
        );

        if ($detachLanguages) {
            $this->languages()->detach();
        }

        if ($detaching) {
            $this->identities()->detach(
                $this->identities()
                    ->where('cluster', $cluster)
                    ->pluck('identity_id')
                    ->toArray()
            );
        }

        foreach ($identities as $id) {
            $this->identities()->attach(
                $id,
                ['weight' => $weight === 'equal' ? 1 / count($identities) : null]
            );
        }
    }
}
