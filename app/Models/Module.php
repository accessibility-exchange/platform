<?php

namespace App\Models;

use App\Traits\GeneratesMultilingualSlugs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Module extends Model
{
    use GeneratesMultilingualSlugs;
    use HasFactory;
    use HasTranslations;
    use HasTranslatableSlug;

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
        'video',
        'slug',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (Module $model, $locale): string {
                return $this->generateSlugs($model, $locale, 'title');
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('started_content_at', 'finished_content_at')
            ->withTimestamps();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
