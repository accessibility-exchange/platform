<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Module extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'introduction',
        'video',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'introduction' => 'array',
        'video' => 'array',
    ];

    public array $translatable = [
        'title',
        'description',
        'introduction',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('started_content_at', 'finished_content_at', 'completed_at')
            ->withTimestamps();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
