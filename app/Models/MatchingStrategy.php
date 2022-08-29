<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MatchingStrategy extends Model
{
    use HasFactory;

    public function criteria(): HasMany
    {
        return $this->hasMany(Criterion::class);
    }

    public function matchable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'matchable_type', 'matchable_id');
    }

    public function locationSummary(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // TODO Handle all cases.
                return __('All provinces and territories.');
            },
        );
    }

    public function disabilityAndDeafGroupSummary(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // TODO Handle all cases.
                return __('Cross disability (includes people with disabilities, Deaf people, and supporters).');
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
