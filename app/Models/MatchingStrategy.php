<?php

namespace App\Models;

use App\Enums\ProvinceOrTerritory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class MatchingStrategy extends Model
{
    use HasFactory;

    protected $fillable = [
        'regions',
        'locations',
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

    public function locationType(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
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
            get: function ($value, $attributes) {
                if (count($this->regions ?? [])) {
                    if (count($this->regions) === 13) {
                        return __('All provinces and territories.');
                    } else {
                        $regions = Arr::map($this->regions, fn ($region) => ProvinceOrTerritory::labels()[$region]);

                        return implode(', ', Arr::sort($regions));
                    }
                } elseif (count($this->locations ?? [])) {
                    $locations = Arr::map($this->locations ?? [], fn ($location) => $location['locality'].', '.ProvinceOrTerritory::labels()[$location['region']]);

                    return implode("  \n", Arr::sort($locations));
                }

                return __('All provinces and territories.');
            },
        );
    }

    public function disabilityAndDeafGroupSummary(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if ($this->hasDisabilityType(DisabilityType::where('name->en', 'Cross-disability')->first())) {
                    return __('Cross disability (includes people with disabilities, Deaf people, and supporters).');
                } else {
                    $disabilityAndDeafGroups = $this->criteria()->where('criteriable_type', 'App\Models\DisabilityType')->get()->map(fn ($group) => $group->criteriable->name)->toArray();

                    return implode("  \n", $disabilityAndDeafGroups);
                }
            },
        );
    }

    public function otherIdentitiesSummary(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // TODO Handle all cases.
                return __('Intersectional.');
            },
        );
    }
}
