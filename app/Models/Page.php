<?php

namespace App\Models;

use App\Traits\GeneratesMultilingualSlugs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use GeneratesMultilingualSlugs;
    use HasFactory;
    use HasTranslatableSlug;
    use HasTranslations;

    protected $fillable = [
        'title',
        'content',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
    ];

    public mixed $translatable = [
        'title',
        'content',
        'slug',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (Page $model, $locale): string {
                return $this->generateSlugs($model, $locale, 'title');
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
