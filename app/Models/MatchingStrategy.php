<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingStrategy extends Model
{
    use HasFactory;

    /**
     * Get the comments for the blog post.
     */
    public function criteria()
    {
        return $this->hasMany(Criterion::class);
    }

    /**
     * Get the model that the matching strategy belongs to.
     */
    public function matchable()
    {
        return $this->morphTo(__FUNCTION__, 'matchable_type', 'matchable_id');
    }
}
