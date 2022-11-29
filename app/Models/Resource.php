<?php

namespace App\Models;

use App\Enums\ConsultationPhase;
use App\Enums\ResourceFormat;
use App\Traits\GeneratesMultilingualSlugs;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Resource extends Model
{
    use GeneratesMultilingualSlugs;
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
        'author',
        'user_id',
        'summary',
        'formats',
        'phases',
        'url',
    ];

    protected $casts = [
        'title' => 'array',
        'author' => 'array',
        'summary' => 'array',
        'formats' => 'array',
        'phases' => 'array',
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
        'url',
        'author',
    ];

    /**
     * Get the options for generating the slug.
     *
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (Resource $model, $locale): string {
                return $this->generateSlugs($model, $locale, 'title');
            })
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

    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }

    public function impacts(): BelongsToMany
    {
        return $this->belongsToMany(Impact::class)->withTimestamps();
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class)->withTimestamps();
    }

    /**
     * @return string
     */
    public function published(): string
    {
        return Carbon::parse($this->created_at)->format('F j, Y');
    }

    public function displayFormats(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_map(fn ($format) => ResourceFormat::labels()[$format], $this->formats),
        );
    }

    public function displayPhases(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_map(fn ($phase) => ConsultationPhase::labels()[$phase], $this->phases),
        );
    }
}
