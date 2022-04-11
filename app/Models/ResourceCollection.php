<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\ResourceCollection
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Resource[] $resources
 */
class ResourceCollection extends Model
{
    use HasFactory;
    use HasSlug;
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'user_id',
        'description',
    ];

    /**
     * The attributes that are transterms
     *
     * @var array
     */
    public $translatable = [
        'title',
        'description',
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
     * Get all of the resources that are assigned this collection.
     */
    public function resources()
    {
        return $this->belongsToMany(Resource::class);
    }
}
