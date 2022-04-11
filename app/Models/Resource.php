<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Resource extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'user_id',
        'content_type_id',
        'summary',
    ];

    /**
     * Get the options for generating the slug.
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
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get all of the resource collections for the resource.
     */
    public function resourceCollections()
    {
        return $this->belongsToMany(ResourceCollection::class);
    }

    /**
     * Get all of the formats for the resource.
     */
    public function formats()
    {
        return $this->morphToMany(Format::class, 'formattable')->withPivot('original', 'language');
    }

    public function originalFormat()
    {
        return $this->morphToMany(Format::class, 'formattable')->wherePivot('original', true)->withPivot('language');
    }

    /**
     * Get all of the formats for the resource.
     */
    public function phases()
    {
        return $this->morphToMany(Phase::class, 'phaseable');
    }

    /**
     * Get all of the formats for the resource.
     */
    public function topics()
    {
        return $this->morphToMany(Topic::class, 'topicable');
    }

    /**
     * Get the content time for the resource.
     */
    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }

    public function published()
    {
        return Carbon::parse($this->created_at)->format('F j, Y');
    }
}
