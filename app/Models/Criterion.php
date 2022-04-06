<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'matching_strategy_id',
        'criteriable_type',
        'criteriable_id',
    ];

    /**
     * Get the matching strategy that owns the criterion.
     */
    public function matchingStrategy()
    {
        return $this->belongsTo(MatchingStrategy::class);
    }

    /**
     * Get the model that the criterion belongs to.
     */
    public function criteriable()
    {
        return $this->morphTo();
    }
}
