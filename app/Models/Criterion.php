<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Criterion extends Model
{
    use HasFactory;

    protected $fillable = [
        'matching_strategy_id',
        'criteriable_type',
        'criteriable_id',
    ];

    public function matchingStrategy(): BelongsTo
    {
        return $this->belongsTo(MatchingStrategy::class);
    }

    public function criteriable(): MorphTo
    {
        return $this->morphTo();
    }
}
