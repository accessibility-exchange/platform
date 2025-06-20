<?php

namespace App\Models;

use App\Traits\GeneratesMultilingualSlugs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Library extends Model implements Sortable
{
    use GeneratesMultilingualSlugs;
    use HasFactory;
    use HasTranslatableSlug;
    use HasTranslations;
    use SortableTrait;

    protected $fillable = [
        'title',
        'description',
        'order_column',
        'featured',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'featured' => 'boolean',
    ];

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

    public mixed $translatable = [
        'title',
        'slug',
        'description',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (Library $model, $locale): string {
                return $this->generateSlugs($model, $locale, 'title');
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resourceCollections(): BelongsToMany
    {
        return $this->belongsToMany(ResourceCollection::class);
    }
}
