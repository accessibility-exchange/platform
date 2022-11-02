<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;

class Module extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'title',
        'introduction',
        'video',
    ];

    protected $casts = [
        'title' => 'array',
        'introduction' => 'array',
        'video' => 'array',
    ];

    public array $translatable = [
        'title',
        'introduction',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }
}
