<?php

namespace App\Models;

use App\Enums\ConsultationPhase;
use App\Enums\ResourceFormat;
use App\Traits\GeneratesMultilingualSlugs;
use Illuminate\Database\Eloquent\Builder;
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

    public mixed $translatable = [
        'title',
        'slug',
        'summary',
        'url',
        'author',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::createWithLocales(config('locales.supported'))
            ->generateSlugsFrom(function (Resource $model, $locale): string {
                return $this->generateSlugs($model, $locale, 'title');
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function authorOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

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

    public function scopeWhereLanguages(Builder $query, array $languages)
    {
        $method = 'whereNotNull';

        foreach ($languages as $language) {
            $query->$method('url->'.$language);

            $method = 'orWhereNotNull';
        }

        return $query;
    }

    public function scopeWhereTopics(Builder $query, array $topics)
    {
        $method = 'whereHas';

        foreach ($topics as $topic) {
            $query->$method('topics', function (Builder $topicQuery) use ($topic) {
                $topicQuery->where('topic_id', $topic);
            });

            $method = 'orWhereHas';
        }

        return $query;
    }

    public function scopeWherePhases(Builder $query, array $phases)
    {
        $method = 'whereJsonContains';

        foreach ($phases as $phase) {
            $query->$method('phases', $phase);

            $method = 'orWhereJsonContains';
        }

        return $query;
    }

    public function scopeWhereContentTypes(Builder $query, array $contentTypes)
    {
        return $query->whereIn('content_type_id', $contentTypes);
    }

    public function scopeWhereSectors(Builder $query, array $sectors)
    {
        $method = 'whereHas';

        foreach ($sectors as $sector) {
            $query->$method('sectors', function (Builder $sectorQuery) use ($sector) {
                $sectorQuery->where('sector_id', $sector);
            });

            $method = 'orWhereHas';
        }

        return $query;
    }

    public function scopeWhereImpacts(Builder $query, array $impacts)
    {
        $method = 'whereHas';

        foreach ($impacts as $impact) {
            $query->$method('impacts', function (Builder $impactQuery) use ($impact) {
                $impactQuery->where('impact_id', $impact);
            });

            $method = 'orWhereHas';
        }

        return $query;
    }
}
