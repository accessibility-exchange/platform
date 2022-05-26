<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Resource extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasTranslatableSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'user_id',
        'summary',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array<string>
     */
    public mixed $translatable = [
        'title',
        'slug',
        'summary',
    ];

    /**
     * Get the options for generating the slug.
     *
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get all the resource collections that include this resource.
     *
     * @return BelongsToMany
     */
    public function resourceCollections(): BelongsToMany
    {
        return $this->belongsToMany(ResourceCollection::class);
    }

    /**
     * Get all the formats for the resource.
     *
     * @return MorphToMany
     */
    public function formats(): MorphToMany
    {
        return $this->morphToMany(Format::class, 'formattable')->withPivot('original', 'language');
    }

    /**
     * Get the original format of the resource.
     *
     * @return MorphToMany
     */
    public function originalFormat(): MorphToMany
    {
        return $this->morphToMany(Format::class, 'formattable')->wherePivot('original', true)->withPivot('language');
    }

    /**
     * Get all the formats for the resource.
     *
     * @return MorphToMany
     */
    public function phases(): MorphToMany
    {
        return $this->morphToMany(Phase::class, 'phaseable');
    }

    /**
     * Get all the formats for the resource.
     *
     * @return MorphToMany
     */
    public function topics(): MorphToMany
    {
        return $this->morphToMany(Topic::class, 'topicable');
    }

    /**
     * Get the content time for the resource.
     *
     * @return BelongsTo
     */
    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * @return string
     */
    public function published(): string
    {
        return Carbon::parse($this->created_at)->format('F j, Y');
    }
}
