<?php

namespace App\Models;

use App\Traits\GeneratesMultilingualSlugs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Tool
 *
 * @property string $title
 */
class Tool extends Model
{
    use GeneratesMultilingualSlugs;
    use HasFactory;
    use HasTranslatableSlug;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'content',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'content' => 'array',
    ];

    public mixed $translatable = [
        'title',
        'slug',
        'description',
        'content',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (Tool $model, $locale): string {
                return $this->generateSlugs($model, $locale, 'title');
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function revisions(): HasManyThrough
    {
        return $this->hasManyThrough(Revision::class, Document::class);
    }
}
