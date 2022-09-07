<?php

namespace App\Models;

use App\Enums\ProvinceOrTerritory;
use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function criteria(): HasMany
    {
        return $this->hasMany(Criterion::class);
    }

    public function matchable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'matchable_type', 'matchable_id');
    }

    public function hasDisabilityTypes(): bool
    {
        return $this->criteria()->where('criteriable_type', DisabilityType::class)->count() > 0;
    }

    public function hasDisabilityType(DisabilityType $disabilityType): bool
    {
        return $this->criteria()->where('criteriable_type', DisabilityType::class)->with('criteriable')->get()->pluck('criteriable')->contains($disabilityType);
    }

    public function hasCriterion(string $model, mixed $criteriable): bool
    {
        return $this->criteria()->where('criteriable_type', $model)->with('criteriable')->get()->pluck('criteriable')->contains($criteriable);
    }

    public function hasCriteria(string $model, array $criteriables): bool
    {
        $items = $model::whereIn(
            'id',
            Arr::map($criteriables, fn ($criteriable) => $criteriable->id)
        )->get();

        return $this->criteria()->where('criteriable_type', $model)->with('criteriable')->get()->pluck('criteriable')->intersect($items)->count() === $items->count();
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
                if ($this->hasDisabilityType(DisabilityType::where('name->en', 'Cross-disability')->first())) {
                    return __('Cross disability (includes people with disabilities, Deaf people, and supporters)');
                }

                if ($this->hasDisabilityTypes()) {
                    return implode("  \n", $this->criteria()->where('criteriable_type', 'App\Models\DisabilityType')->get()->map(fn ($group) => $group->criteriable->name)->toArray());
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
                    'age-bracket' => implode("  \n", $this->criteria()->where('criteriable_type', 'App\Models\AgeBracket')->get()->map(fn ($group) => $group->criteriable->name)->toArray()),
                    'gender-and-sexual-identity' => implode("  \n", $this->criteria()->whereIn('criteriable_type', ['App\Models\Constituency', 'App\Models\GenderIdentity'])->get()->map(fn ($group) => $group->criteriable->name_plural)->toArray()),
                    'indigenous-identity' => implode("  \n", $this->criteria()->where('criteriable_type', 'App\Models\IndigenousIdentity')->get()->map(fn ($group) => $group->criteriable->name)->toArray()),
                    'ethnoracial-identity' => implode("  \n", $this->criteria()->where('criteriable_type', 'App\Models\EthnoracialIdentity')->get()->map(fn ($group) => $group->criteriable->name)->toArray()),
                    'refugee-or-immigrant' => implode("  \n", $this->criteria()->where('criteriable_type', 'App\Models\Constituency')->get()->map(fn ($group) => $group->criteriable->name_plural)->toArray()),
                    'first-language' => implode("  \n", $this->criteria()->where('criteriable_type', 'App\Models\Language')->get()->map(fn ($group) => $group->criteriable->name)->toArray()),
                    'area-type' => implode("  \n", $this->criteria()->where('criteriable_type', 'App\Models\AreaType')->get()->map(fn ($group) => $group->criteriable->name)->toArray()),
                    default => __('Intersectional')
                };
            },
        );
    }

    /**
     * Synchronize associations with criteria from a single criteriable model.
     */
    public function syncRelatedCriteria(string $model, int|array $criteria, ?string $weight = null, bool $detaching = true): void
    {
        if (! is_array($criteria)) {
            $criteria = [$criteria];
        }

        if ($detaching) {
            Criterion::destroy(
                $this->criteria()
                    ->whereNotIn('criteriable_id', $criteria)
                    ->where('criteriable_type', $model)
                    ->get()
            );
        }

        foreach ($criteria as $id) {
            $this->criteria()->updateOrCreate(
                ['criteriable_type' => $model, 'criteriable_id' => $id],
                ['weight' => $weight === 'equal' ? 1 / count($criteria) : null]
            );
        }
    }

    /**
     * Synchronize associations with criteria from a single criteriable model,
     * removing associations from other, mutually-exclusive criteriable models.
     */
    public function syncMutuallyExclusiveCriteria(string $model, int|array $criteria, array $mutuallyExclusiveModels, ?string $weight = null, bool $detaching = true): void
    {
        if (! is_array($criteria)) {
            $criteria = [$criteria];
        }

        Criterion::destroy(
            $this->criteria()->whereIn('criteriable_type', $mutuallyExclusiveModels)->get()
        );

        if ($detaching) {
            Criterion::destroy(
                $this->criteria()
                    ->whereNotIn('criteriable_id', $criteria)
                    ->where('criteriable_type', $model)
                    ->get()
            );
        }

        foreach ($criteria as $id) {
            $this->criteria()->updateOrCreate(
                ['criteriable_type' => $model, 'criteriable_id' => $id],
                ['weight' => $weight === 'equal' ? 1 / count($criteria) : null]
            );
        }
    }

    /**
     * Synchronize associations with criteria from a multiple criteriable models,
     * optionally removing associations from other, mutually-exclusive criteriable models.
     */
    public function syncUnrelatedMutuallyExclusiveCriteria(array $criteria, ?array $mutuallyExclusiveModels, ?string $weight = null, bool $detaching = true): void
    {
        if ($mutuallyExclusiveModels) {
            Criterion::destroy(
                $this->criteria()->whereIn('criteriable_type', $mutuallyExclusiveModels)->get()
            );
        }

        if ($detaching) {
            foreach ($criteria as $model => $ids) {
                Criterion::destroy(
                    $this->fresh()->criteria()
                        ->whereNotIn('criteriable_id', $ids)
                        ->where('criteriable_type', $model)
                        ->get()
                );
            }
        }

        foreach ($criteria as $model => $ids) {
            foreach ($ids as $id) {
                $this->criteria()->updateOrCreate(
                    ['criteriable_type' => $model, 'criteriable_id' => $id],
                    ['weight' => $weight === 'equal' ? 1 / count($ids) : null]
                );
            }
        }
    }
}
