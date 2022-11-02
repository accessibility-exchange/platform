<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Quiz extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $attributes = [
        'minimum_score' => 0.75,
    ];

    protected $fillable = [
        'minimum_score',
        'title',
    ];

    protected $casts = [
        'minimum_score' => 'float',
        'title' => 'array',
    ];

    public array $translatable = [
        'title',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
