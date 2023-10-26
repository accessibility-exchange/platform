<?php

namespace App\Models;

use App\Traits\GeneratesMultilingualSlugs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class ResourceCollection extends Model
{
    use GeneratesMultilingualSlugs;
    use HasFactory;
    use HasTranslatableSlug;
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
    ];

    public mixed $translatable = [
        'title',
        'slug',
        'description',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (ResourceCollection $model, $locale): string {
                return $this->generateSlugs($model, $locale, 'title');
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class);
    }
}
